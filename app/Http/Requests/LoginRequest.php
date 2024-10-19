<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ];
    }

    public function messages(): array
    {
        // todo: изменить текста если нужно
        return [
//            'email.required' => 'Электронная почта обязательна для заполнения.',
//            'email.email' => 'Введите корректный адрес электронной почты.',
//            'password.required' => 'Пароль обязателен для заполнения.',
//            'password.min' => 'Пароль должен содержать не менее :min символов.',
        ];
    }

    protected function passedValidation()
    {
        // Проверка учетных данных пользователя
        if (!Auth::attempt($this->only('email', 'password'))) {
            return response()->json([
                'message' => 'Invalid login credentials',
            ], Response::HTTP_UNAUTHORIZED);
        }
    }
}
