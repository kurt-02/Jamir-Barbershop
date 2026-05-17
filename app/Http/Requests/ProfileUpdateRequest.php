<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'email' => [
                'nullable',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
            'contact_number' => [
                'nullable',
                'string',
                'size:10',
                'regex:/^9\d{9}$/',
            ],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if (empty($this->email) && empty($this->contact_number)) {
                $validator->errors()->add('email', 'You must provide either an email or contact number.');
                $validator->errors()->add('contact_number', 'You must provide either an email or contact number.');
            }
        });
    }
}
