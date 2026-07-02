<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Chapter extends Model
{
    protected $fillable = [
        'work_id', 'title', 'order', 'content', 'audio_url', 'video_url',
        'duration_seconds', 'is_premium', 'price_credit',
        'status', 'scheduled_at', 'published_at',
    ];

    protected $casts = [
        'is_premium' => 'boolean',
        'scheduled_at' => 'datetime',
        'published_at' => 'datetime',
    ];

    public function work(): BelongsTo
    {
        return $this->belongsTo(Work::class);
    }

    public function unlocks(): HasMany
    {
        return $this->hasMany(Unlock::class);
    }

    /** Apakah user tertentu sudah membuka chapter ini */
    public function isUnlockedBy(?User $user): bool
    {
        if (! $this->is_premium) {
            return true;
        }
        if (! $user) {
            return false;
        }
        // Kreator selalu bisa akses karyanya sendiri
        if ($user->id === $this->work->creator_id) {
            return true;
        }
        return $this->unlocks()->where('user_id', $user->id)->exists();
    }
}
