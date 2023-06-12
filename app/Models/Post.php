<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasFactory;

    protected $fillable = ['title','news_content','user_id'];
    
    public function writter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
