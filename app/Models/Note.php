<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'type', // normal | vocab | formula
        'subject',
        'is_private',
        'is_completed',
        'page_limit',
    ];

    protected $casts = [
        'is_private' => 'boolean',
        'is_completed' => 'boolean',
        'page_limit' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pages()
    {
        return $this->hasMany(Page::class);
    }

    public function studySessions()
    {
        return $this->hasMany(StudySession::class);
    }
}
