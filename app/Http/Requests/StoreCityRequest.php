<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCityRequest extends FormRequest
{
    /**  
     * Determina se o usuário tem permissão
     * Qualquer usuário pode criar uma cidade 
    * */
    public function authorize(): bool
    {
        return true;
    }

    /**  
     * Validação dos campos para criação e atualização de cidades
    **/
    public function rules(): array
    {
        return [
            'name'      => ['required', 'string', 'max:255'],
            'ddd'       => ['required', 'string', 'size:2'],
            'ibge_code' => ['required', 'string', 'size:7'],
            'uf_id'     => ['required', 'exists:ufs,id'],
            'zip_code'  => ['required', 'string', 'size:8', 'unique:cities,zip_code,' . $this->route('city')?->id],
        ];
    }

    public function messages(): array
    {
        return [
            'zip_code.unique' => 'Este CEP já está cadastrado.',
            'zip_code.size'   => 'O CEP deve conter exatamente 8 dígitos.',
        ];
    }
}
