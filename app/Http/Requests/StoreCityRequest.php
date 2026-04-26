<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'name'      => ['required', 'string', 'max:250'],
            'ddd'       => ['required', 'string', 'size:2'],
            'ibge_code' => ['required', 'string', 'size:7'],
            'uf_id'     => ['required', 'exists:ufs,id'],
            'zip_code'  => ['required', 'string', 'size:8', Rule::unique('cities', 'zip_code')->ignore($this->route('city'))],
            'ibge_code' => ['required', 'string', 'size:7', Rule::unique('cities', 'ibge_code')->ignore($this->route('city'))],
        ];
    }

    public function messages(): array
    {
        return [
                'name.required'      => 'O nome da cidade é obrigatório.',
                'name.max'           => 'O nome não pode ter mais de 250 caracteres.',
                'ddd.required'       => 'O DDD é obrigatório.',
                'ddd.size'           => 'O DDD deve ter exatamente 2 dígitos.',
                'uf_id.required'     => 'Selecione um estado.',
                'uf_id.exists'       => 'O estado selecionado é inválido.',
                'ibge_code.required' => 'O código IBGE é obrigatório.',
                'ibge_code.size'     => 'O código IBGE deve ter exatamente 7 dígitos.',
                'ibge_code.unique'   => 'Este código IBGE já está cadastrado.',
                'zip_code.required'  => 'O CEP é obrigatório.',
                'zip_code.size'      => 'O CEP deve conter exatamente 8 dígitos.',
                'zip_code.unique'    => 'Este CEP já está cadastrado em outra cidade.',
            ];
    }
}
