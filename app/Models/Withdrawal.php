<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Withdrawal extends Model
{
    protected $fillable = [
        'user_id', 'destination_type', 'destination_name', 'account_number',
        'account_holder', 'amount', 'fee', 'net_amount', 'status', 'note',
        'processed_by', 'processed_at',
    ];

    protected $casts = ['processed_at' => 'datetime'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
