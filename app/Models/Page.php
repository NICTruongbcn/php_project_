<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;

    protected $fillable = [
        'note_id',
        'position',
        'front_text',
        'back_text',
        'front_latex',
        'back_latex',
        'image_front',
        'image_back',
        'source',
        'meta',
    ];

    protected $casts = [
        'position' => 'integer',
        'meta' => 'array',
    ];

    public function note()
    {
        return $this->belongsTo(Note::class);
    }

    public function attachments()
    {
        return $this->hasMany(Attachment::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function queueItems()
    {
        return $this->hasMany(SessionQueueItem::class, 'page_id');
    }
}
