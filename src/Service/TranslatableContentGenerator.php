<?php

namespace App\Service;

use App\Entity\AbstractTranslatableCategoryEntity;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\PersistentCollection;
use Knp\DoctrineBehaviors\Contract\Entity\TranslatableInterface;

class TranslatableContentGenerator
{
    private EntityManagerInterface $entityManager;
    private string $locale;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->locale = 'pl';
    }

    /**
     * @throws TranslatableContentException
     */

    public function generateTranslatableTextIDArrayContent(string $entity, string $locale): array
    {
        $this->setLocale($locale);

        $contentArray = [];
        $delimiterStrings = $this->getDelimiterStringsForTranslatableEntity($entity);

        $records = $this->findAllForEntity($entity);

        foreach ($records as $record) {
            $textID = $record->getTextID() ?? throw new TranslatableContentException('Record from ' . $entity . ' hasn\'t got TextID');
            $contentArray[$textID] = $this->getDelimitedTranslationArray($delimiterStrings, $record) ?? throw new TranslatableContentException('Record with TextID "' . $textID . '" is not translatable.');
        }
        return $contentArray;
    }

    private function setLocale(string $locale): void
    {
        $this->locale = $locale;
    }

    private function getDelimiterStringsForTranslatableEntity(string $entity): array
    {
        $entityName = $this->getNameAfterLastString('\\', $entity);

        $delimiterStrings['entity'] = $entityName . "\x00";
        $delimiterStrings['translation'] = $entityName . "Translation\x00";

        return $delimiterStrings;
    }

    private function getNameAfterLastString(string $stringDelimiter, string $oldString): string
    {
        $explodedArray = explode($stringDelimiter, $oldString);
        return $explodedArray[count($explodedArray) - 1] ?? $oldString;
    }

    private function findAllForEntity(string $entity): array
    {
        $repository = $this->entityManager->getRepository($entity);
        return $repository->findAll();
    }

    private function getDelimitedTranslationArray(array $delimiterStrings, TranslatableInterface $translatableEntity): array
    {
        $delimitedRecordArray = $this->delimitedRecordArray($delimiterStrings['entity'], (array)$translatableEntity);
        $delimitedTranslationRecordArray = $this->delimitedRecordArray($delimiterStrings['translation'], (array)$translatableEntity->translate($this->locale));
        return $delimitedRecordArray + $delimitedTranslationRecordArray;
    }

    private function delimitedRecordArray(string $keyArrayDelimiter, array $array): array
    {
        foreach ($array as $key => $value) {
            if ($value) {
                $array[$this->getNameAfterLastString($keyArrayDelimiter, $key)] = $value;
            }
            unset($array[$key]);
        }
        return $array;
    }

    /**
     * @throws TranslatableContentException
     */
    public function generateTranslatableContent(string $entity, string $locale): array
    {
        $this->setLocale($locale);
        $contentArray = [];
        $delimiterStrings = $this->getDelimiterStringsForTranslatableEntity($entity);
        $records = $this->findAllForEntity($entity);

        foreach ($records as $record)
            array_push($contentArray, $this->getDelimitedTranslationArray($delimiterStrings, $record));

        return $contentArray;
    }

    /**
     * @throws TranslatableContentException
     */
    public function generateTranslatableCollectionContent(string $categoryEntity, string $locale): array
    {
        $this->setLocale($locale);

        $contentArray = [];
        $categories = $this->findAllForEntity($categoryEntity);

        $categoryDelimiters = $this->getDelimiterStringsForTranslatableEntity($categoryEntity);

        /** @var AbstractTranslatableCategoryEntity $category */
        foreach ($categories as $category) {
            $categoryArray = $this->getDelimitedTranslationArray($categoryDelimiters, $category);

            $collectionKey = $this->getKeyOfClassFromArray($categoryArray, PersistentCollection::class);

            $categoryArray[$collectionKey] = $this->getTranslatedCollectionArrayForCategoryRecord($category);

            array_push($contentArray, $categoryArray);
        }
        return $contentArray;
    }

    /**
     * @throws TranslatableContentException
     */

    private function getKeyOfClassFromArray(array $assocArray, string $categoryClass): string
    {
        foreach ($assocArray as $key => $value) {
            if (is_object($value) && get_class($value) == $categoryClass)
                return $key;
        }
        throw new TranslatableContentException('Array doesn\'t have ' . $categoryClass . ' collection value');
    }

    /**
     * @throws TranslatableContentException
     */
    private function getTranslatedCollectionArrayForCategoryRecord(AbstractTranslatableCategoryEntity $category): array
    {
        $collectionArray = [];

        $translatableCollection = $category->getTranslatableCollection();
        $collectionEntityName = $this->getCollectionEntityName($translatableCollection);

        $collectionDelimiters = $this->getDelimiterStringsForTranslatableEntity($collectionEntityName);

        /** @var TranslatableInterface $collectionRecord */
        foreach ($translatableCollection as $collectionRecord)
            array_push($collectionArray, $this->getDelimitedCollectionTranslationArray($collectionDelimiters, $collectionRecord, get_class($category)));

        return $collectionArray;
    }

    /**
     * @throws TranslatableContentException
     */
    private function getDelimitedCollectionTranslationArray(array $collectionDelimiters, TranslatableInterface $collectionRecord, string $categoryEntityToUnset): array
    {
        $delimitedCollectionRecordArray = $this->delimitedRecordArray($collectionDelimiters['entity'], (array)$collectionRecord);
        $categoryKeyToUnset = $this->getKeyOfClassFromArray($delimitedCollectionRecordArray, $categoryEntityToUnset);

        unset($delimitedCollectionRecordArray[$categoryKeyToUnset]);

        $delimitedTranslationCollectionRecordArray = $this->delimitedRecordArray($collectionDelimiters['translation'], (array)$collectionRecord->translate($this->locale));
        return $delimitedCollectionRecordArray + $delimitedTranslationCollectionRecordArray;
    }

    /**
     * @throws TranslatableContentException
     */
    private function getCollectionEntityName(Collection $translatableCollection): string
    {
        if (count($translatableCollection) > 0) {
            return get_class($translatableCollection[0]);
        }
        throw new TranslatableContentException('Empty collection for category');
    }
}