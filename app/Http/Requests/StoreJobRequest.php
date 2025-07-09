<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Job;

class StoreJobRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user()->can('create', Job::class);
    }

    public function rules()
    {
        return [
            'fault_description' => 'required|string|max:2000',
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'customer_address' => 'nullable|string|max:500',
            'amount_charged' => 'required|numeric|min:0',
            'type' => 'required|in:maintenance,repair,installation,consultation,emergency',
            'priority' => 'required|in:low,medium,high,urgent',
            'zimra_ref' => 'nullable|string|max:100'
        ];
    }

    public function messages()
    {
        return [
            'fault_description.required' => 'Please provide a detailed description of the fault or service required.',
            'customer_email.email' => 'Please enter a valid email address.',
            'amount_charged.required' => 'Please specify the amount to be charged for this service.',
            'amount_charged.numeric' => 'Amount must be a valid number.',
            'amount_charged.min' => 'Amount cannot be negative.',
        ];
    }
}
