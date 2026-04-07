<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFiliadoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * A autorização é verificada via Policy no Controller.
     */
    public function authorize(): bool
    {
        // Retorna true para que a Policy no Controller decida
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $filiadoId = $this->route('filiado')->id;

        return [
            'matricula' => "required|integer|unique:filiados,matricula,{$filiadoId}", // Ignora o ID atual
            'nome' => 'required|string|max:255',
            'documento' => 'required|string|max:14|min:11',
            'telefone' => 'required|string|max:15',
            'email' => 'nullable|email|max:255',
            'dataNascimento' => 'required|date|before:today',
            'logradouro' => 'required|string|max:50',
            'endereco' => 'required|string|max:255',
            'numero' => 'required|integer',
            'bairro' => 'required|string|max:255',
            'cep' => 'required|string|max:9',
            'cidade' => 'required|string|max:255',
            'uf' => 'required|string|size:2',
            'estadoCivil' => 'required|string|max:255',
            'mae' => 'required|string|max:255',
            'pai' => 'nullable|string|max:255',
            'dataBatismo' => 'nullable|date|after_or_equal:dataNascimento',
            'data_Consagracao' => 'nullable|date|after_or_equal:dataBatismo',
            'funcao' => 'required|string|max:255',
            'congregacao' => 'required|string|max:255',
            'status' => 'required|string|max:50',
            // 'arquivo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Exemplo para upload de imagem
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'matricula.unique' => 'A matrícula informada já está em uso por outro filiado.',
            'dataNascimento.before' => 'A data de nascimento deve ser anterior à data de hoje.',
            'documento.min' => 'O campo documento deve ter pelo menos 11 caracteres (CPF).',
            'documento.max' => 'O campo documento deve ter no máximo 14 caracteres (CPF com máscara).',
            'email.email' => 'O campo email deve ser um endereço de e-mail válido.',
            'uf.size' => 'O campo UF deve ter exatamente 2 caracteres.',
            'dataBatismo.after_or_equal' => 'A data de batismo deve ser igual ou posterior à data de nascimento.',
            'data_Consagracao.after_or_equal' => 'A data de consagração deve ser igual ou posterior à data de batismo.',
            // ... outras mensagens personalizadas ...
        ];
    }
}