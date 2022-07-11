<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;

class TranslatableContentGenerator
{
    private EntityManagerInterface $entityManager;
    private string $delimiterString;
    private string $delimiterTranslationString;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;

    }

    /**
     * @throws NonExistingTextIDException
     */
//    TODO: without $textIDArray, just all IDs
    public function generateContentArrayForTextID(string $entity, array $textIDArray, string $locale): array
    {
        $this->setDelimiterStringsForEntity($entity);

        $contentArray = [];
        $repository = $this->entityManager->getRepository($entity);

        foreach ($textIDArray as $textID) {
            $record = $repository->findOneBy(['textID' => $textID]) ?? throw new NonExistingTextIDException('TextID "' . $textID . '" doesn\'t exist in ' . $entity);
            $recordTranslation = $record->translate($locale);

            $preparedRecordArray = $this->prepareRecordArray($this->delimiterString, (array)$record);
            $preparedTranslationRecordArray = $this->prepareRecordArray($this->delimiterTranslationString, (array)$recordTranslation);

            $contentArray[$textID] = $preparedRecordArray + $preparedTranslationRecordArray;
        }
        return $contentArray;
    }

    private function setDelimiterStringsForEntity(string $entity): void
    {
        $entityName = $this->getNameAfterLastString('\\', $entity);
        $this->delimiterString = $entityName . "\x00";
        $this->delimiterTranslationString = $entityName . "Translation\x00";
    }

    private function getNameAfterLastString(string $stringDelimiter, string $oldString): string
    {
        $explodedArray = explode($stringDelimiter, $oldString);
        return $explodedArray[count($explodedArray) - 1] ?? $oldString;
    }

    private function prepareRecordArray(string $keyArrayDelimiter, array $array): array
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