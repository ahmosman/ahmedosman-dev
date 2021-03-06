<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
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
}