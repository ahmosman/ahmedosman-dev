<?php

namespace App\Service;

class TranslatableDashboardFieldsGenerator extends DashboardFieldsGenerator
{
    private string $locale;
    private array $translatableFields;

    public function __construct(
        $entityRecords,
        array $normalFields,
        array $translatableFields,
        string $locale
    )
    {
        parent::__construct($entityRecords, $normalFields);
        $this->translatableFields = $translatableFields;
        $this->locale = $locale;
    }

    public function generate(): array
    {
        $generatedTable = [];
        foreach ($this->entityRecords as $record) {
            $entityRecordMergedArray = array_merge($this->prepareArrayFromRecord($record), $this->prepareTranslatableArrayFromRecord($record->translate($this->locale, false)));
            array_push($generatedTable, $entityRecordMergedArray);
        }

        return $generatedTable;
    }

    private function prepareTranslatableArrayFromRecord($translatedRecord): array
    {
        $substrArray = $this->substrKeysFromArrayByLastNullChar((array)$translatedRecord);

        return $this->filterArrayByKeys($substrArray, $this->translatableFields);
    }

}