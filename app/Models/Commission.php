<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Commission extends Model
{
    protected $fillable = ['affiliate_id', 'unlock_id', 'amount_rupiah'];

    public function affiliate(): BelongsTo
    {
        return $this->belongsTo(User::class, 'affiliate_id');
    }

    public function unlock(): BelongsTo
    {
        return $this->belongsTo(Unlock::class);
    }
}
