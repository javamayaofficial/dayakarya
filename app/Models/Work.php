<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Work extends Model
{
    protected $fillable = [
        'creator_id', 'category_id', 'title', 'slug', 'type',
        'synopsis', 'cover', 'status', 'is_featured',
        'views', 'likes_count', 'published_at',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'published_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (Work $work) {
            $work->slug ??= Str::slug($work->title) . '-' . Str::random(5);
        });
    }

    public function isAudio(): bool
    {
        return in_array($this->type, config('dayakarya.audio_types', []));
    }

    public function isVideo(): bool
    {
        return in_array($this->type, config('dayakarya.video_types', []));
    }

    // ---- Relasi ----
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function chapters(): HasMany
    {
        return $this->hasMany(Chapter::class)->orderBy('order');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    // ---- Scope ----
    public function scopePublished($q)
    {
        return $q->where('status', 'published');
    }

    public function scopeTrending($q)
    {
        return $q->published()->orderByDesc('views')->orderByDesc('likes_count');
    }
}
