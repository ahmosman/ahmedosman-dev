<?php

namespace App\Service;

class DashboardFieldsGenerator
{
    protected $entityRecords;
    protected array $normalFields;

    public function __construct($entityRecords, array $normalFields)
    {
        $this->entityRecords = $entityRecords;
        $this->normalFields = $normalFields;
    }

    public function generate(): array
    {
        $generatedTable = [];
        foreach ($this->entityRecords as $record) {
            array_push($generatedTable, $this->prepareArrayFromRecord($record));
        }

        return $generatedTable;
    }

    protected function prepareArrayFromRecord($record): array
    {
        $substrArray = $this->substrKeysFromArrayByLastNullChar((array)$record);

        return $this->filterArrayByKeys($substrArray, $this->normalFields);
    }

    protected function substrKeysFromArrayByLastNullChar($array)
    {
        foreach ($array as $key => $value) {
            $array[$this->getNameAfterLastNullChar($key)] = $value;
            unset($array[$key]);
        }

        return $array;
    }

    protected function getNameAfterLastNullChar(int|string $key): string
    {
        return substr($key, strlen($key) - strpos(strrev($key), "\x00"));
    }

    protected function filterArrayByKeys(array $array, array $allowedKeys): array
    {
        return array_intersect_key($array, array_flip($allowedKeys));
    }
}