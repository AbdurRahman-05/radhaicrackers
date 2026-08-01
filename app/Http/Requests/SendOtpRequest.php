<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SendOtpRequest extends FormRequest
{
    protected function prepareForValidation()
    {
        if ($this->has('phone')) {
            $phone = preg_replace('/[^0-9]/', '', $this->phone);
            if (strlen($phone) === 12 && str_starts_with($phone, '91')) {
                $phone = substr($phone, 2);
            } elseif (strlen($phone) === 11 && str_starts_with($phone, '0')) {
                $phone = substr($phone, 1);
            }
            $this->merge([
                'phone' => $phone,
            ]);
        }
    }

    public function rules()
    {
        return [
            'phone' => ['required', 'regex:/^[6-9]\\d{9}$/'],
            'name' => ['required', 'string', 'max:255', 'regex:/^[^0-9]+$/'],
        ];
    }
} 