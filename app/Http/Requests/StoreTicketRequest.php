<?php

namespace App\Http\Requests;

use App\Models\Ticket;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_id' => ['required', 'exists:customers,id'],
            'manager_id' => ['nullable', 'exists:users,id'],
            'subject' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'status' => ['sometimes', Rule::in(Ticket::getStatuses())],
        ];
    }

    public function messages(): array
    {
        return [
            'customer_id.required' => 'Mijoz tanlanishi shart',
            'customer_id.exists' => 'Tanlangan mijoz topilmadi',
            'subject.required' => 'Mavzu kiritish majburiy',
            'description.required' => 'Ta\'rif kiritish majburiy',
        ];
    }
}
