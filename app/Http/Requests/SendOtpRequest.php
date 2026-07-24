<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SendOtpRequest extends FormRequest
{
    public function rules()
    {
        return [
            'phone' => ['required', 'regex:/^[6-9]\\d{9}$/'],
            'name' => ['required', 'string', 'max:255', 'regex:/^[^0-9]+$/'],
        ];
    }
} 