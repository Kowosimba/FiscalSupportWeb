<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateJobRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user()->can('update', $this->route('job'));
    }

    public function rules()
    {
        $user = $this->user();
        
        if ($user->role === 'technician') {
            return [
                'engineer_comments' => 'nullable|string|max:2000',
                'billed_hours' => 'nullable|numeric|min:0',
                'status' => 'required|in:assigned,in_progress,completed'
            ];
        }
        
        return [
            'fault_description' => 'required|string|max:2000',
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'customer_address' => 'nullable|string|max:500',
            'amount_charged' => 'required|numeric|min:0',
            'type' => 'required|in:maintenance,repair,installation,consultation,emergency',
            'priority' => 'required|in:low,medium,high,urgent',
            'zimra_ref' => 'nullable|string|max:100',
            'assigned_to' => 'nullable|exists:users,id'
        ];
    }
}
