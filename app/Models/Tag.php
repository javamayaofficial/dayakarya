<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Tag extends Model
{
    protected $fillable = ['name', 'slug'];

    protected static function booted(): void
    {
        static::creating(fn (Tag $t) => $t->slug ??= Str::slug($t->name));
    }

    public function works(): BelongsToMany
    {
        return $this->belongsToMany(Work::class);
    }
}
