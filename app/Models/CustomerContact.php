<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerContact extends Model
{
    protected $fillable = [
        'name', 'email', 'phone', 'company', 'position', 'notes', 'is_active',
    ];
}
