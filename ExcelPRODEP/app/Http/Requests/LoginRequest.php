<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'correo_curp' => 'required',
            'password' => 'required',
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @return void
     * @throws ValidationException
     */
    protected function failedValidation($validator)
    {
        throw new ValidationException($validator); // Laravel handles response automatically
    }

    /**
     * Get the login credentials from the request.
     *
     * @return array
     */
    public function getCredentials(): array
    {
        $field = filter_var($this->input('correo_curp'), FILTER_VALIDATE_EMAIL)
            ? 'email'
            : 'curp';

        return [
            $field => $this->input('correo_curp'),
            'password' => $this->input('password'),
        ];
    }
}
