<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WidgetTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:15'],
            'email' => ['nullable', 'email', 'max:255'],
            'subject' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Ismingizni kiriting',
            'phone.required' => 'Telefon raqamingizni kiriting',
            'subject.required' => 'Murojaat mavzusini kiriting',
            'description.required' => 'Murojaat matnini kiriting',
            'email.email' => 'Email manzil noto\'g\'ri',
        ];
    }
}
