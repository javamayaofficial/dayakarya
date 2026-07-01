<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AffiliateLink extends Model
{
    protected $fillable = ['affiliate_id', 'work_id', 'code', 'clicks', 'conversions'];

    public function affiliate(): BelongsTo
    {
        return $this->belongsTo(User::class, 'affiliate_id');
    }

    public function work(): BelongsTo
    {
        return $this->belongsTo(Work::class);
    }
}
