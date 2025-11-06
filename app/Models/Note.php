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
        'type',
        'subject', 
        'description',
        'is_private',
        'is_completed',
        'page_limit'
    ];

    protected $casts = [
        'is_private' => 'boolean',
        'is_completed' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Giá trị mặc định
    protected $attributes = [
        'is_private' => true,
        'is_completed' => false,
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pages()
    {
        return $this->hasMany(Page::class)->orderBy('position');
    }

    public function studySessions()
    {
        return $this->hasMany(StudySession::class);
    }
}