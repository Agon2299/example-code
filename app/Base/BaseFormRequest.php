<?php


namespace App\Base;

use Illuminate\Foundation\Http\FormRequest;

abstract class BaseFormRequest extends FormRequest
{
    abstract public function requestToDto();

    public function authorize() {
        return true;
    }

    public function rules() {
        return [];
    }
}
