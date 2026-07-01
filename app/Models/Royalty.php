<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Royalty extends Model
{
    protected $fillable = ['creator_id', 'unlock_id', 'amount_rupiah'];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function unlock(): BelongsTo
    {
        return $this->belongsTo(Unlock::class);
    }
}
