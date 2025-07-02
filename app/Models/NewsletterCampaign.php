<?php

// app/Models/NewsletterCampaign.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsletterCampaign extends Model
{
    use HasFactory;

    protected $fillable = ['subject', 'content', 'sent_count', 'sent_at'];
    
    protected $casts = [
        'sent_at' => 'datetime', // Add this line
    ];
}