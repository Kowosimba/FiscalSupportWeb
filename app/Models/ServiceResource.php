<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceResource extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_id', 'title', 'file_path', 
        'file_size', 'file_type', 'download_count'
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}