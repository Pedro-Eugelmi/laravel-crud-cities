<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUfRequest extends FormRequest
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
     * Nome e sigla da UF são obrigatórios, únicos e com limites de caracteres
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:50',
                Rule::unique('ufs', 'name')->ignore($this->route('uf'))
            ],
            'state_code' => [
                'required',
                'string',
                'size:2',
                Rule::unique('ufs', 'state_code')->ignore($this->route('uf'))
            ],
        ];
    }

    /**
     * Mensagens de erro personalizadas
     */
    public function messages(): array
    {
        return [
            'name.required' => 'O nome do estado é obrigatório.',
            'name.unique'   => 'Este estado já está cadastrado.',
            'name.max'      => 'O nome do estado deve ter no máximo 50 caracteres.',
            'state_code.required' => 'A sigla (UF) é obrigatória.',
            'state_code.size'     => 'A sigla deve ter exatamente 2 caracteres (ex: SP).',
            'state_code.unique'   => 'Esta sigla já está em uso.',
        ];
    }
}
