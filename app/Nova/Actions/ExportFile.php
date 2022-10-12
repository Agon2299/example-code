<?php

namespace App\Nova\Actions;

use Illuminate\Database\Eloquent\Model;
use Maatwebsite\LaravelNovaExcel\Actions\DownloadExcel;

class ExportFile extends DownloadExcel
{
    public function getFilename(string $filename = null): string
    {
        return $filename ? $filename . $this->getWriterType() : $this->filename;
    }

    public function name()
    {
        return __('Экспорт данных');
    }

    /**
     * @param Model $model
     * @param array $only
     *
     * @return array
     */
    protected function replaceFieldValuesWhenOnResource(Model $model, array $only = []): array
    {
        $resource = $this->resolveResource($model);
        $fields   = $this->resourceFields($resource);

        $row = [];
        foreach ($fields as $field) {
            if (!$this->isExportableField($field)) {
                continue;
            }

            if (in_array($field->attribute, ['thumbnail', 'images'])) {
                continue;
            } elseif (\in_array($field->attribute, $only, true)) {
                $row[$field->attribute] = strip_tags($field->value);
            } elseif (\in_array($field->name, $only, true)) {
                // When no field could be found by their attribute name, it's most likely a computed field.
                $row[$field->name] = strip_tags($field->value);
            }
        }

        // Add fields that were requested by ->only(), but are not registered as fields in the Nova resource.
        foreach (array_diff($only, array_keys($row)) as $attribute) {
            if ($model->{$attribute}) {
                $row[$attribute] = strip_tags($model->{$attribute});
            } else {
                $row[$attribute] = '';
            }
        }

        // Fix sorting
        $row = array_merge(array_flip($only), $row);

        return $row;
    }
}
