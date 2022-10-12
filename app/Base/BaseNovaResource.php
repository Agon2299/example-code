<?php


namespace App\Base;


use App\Nova\Resource;
use Illuminate\Http\Request;

abstract class BaseNovaResource extends Resource
{
    /**
     * Get the fields displayed by the resource.
     *
     * @param Request $request
     * @return array
     */
    public function fields(Request $request): array
    {
        $fields = $this->getFields();
        $idField = [];
        return array_merge($idField, $fields);
    }

    abstract protected function getFields(): array;
}
