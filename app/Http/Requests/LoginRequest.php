<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
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
        return [];
    }

    protected function passedValidation(): void
    {
        // Проверка учетных данных пользователя
        if (!Auth::attempt($this->only('email', 'password'))) {
            throw new HttpResponseException(
                response()->json([
                    'message' => 'Invalid login credentials',
                ], Response::HTTP_UNAUTHORIZED)
            );
        }
    }
}
