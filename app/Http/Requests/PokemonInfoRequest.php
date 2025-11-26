<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;


class PokemonInfoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            'pokemons' => [
                'required',
                'array',
                'min:1',
                'max:50'
            ],
            'pokemons.*' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-z0-9-]+$/'
            ]
        ];
    }

    /**
     * @return string[]
     */
    public function messages(): array
    {
        return [
            'pokemons.required' => 'List of pokemons is required',
            'pokemons.array' => 'Pokemons must be an array',
            'pokemons.min' => 'At least one pokemon is required',
            'pokemons.max' => 'Max 50 pokemons for request',
            'pokemons.*.required' => 'Pokemon name cant be empty',
            'pokemons.*.string' => 'Pokemon name must be a string',
            'pokemons.*.max' => 'Pokemon name max 255 chars',
            'pokemons.*.regex' => 'Pokemon name must be lowercase'
        ];
    }

    /**
     * @param Validator $validator
     * @return mixed
     */
    protected function failedValidation(Validator $validator): mixed
    {
        throw new HttpResponseException(
            response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422)
        );
    }

    /**
     * @return void
     */
    protected function prepareForValidation(): void
    {
        if ($this->has('pokemons') && is_array($this->pokemons)) {
            $this->merge([
                'pokemons' => array_map('strtolower', $this->pokemons)
            ]);
        }
    }
}
