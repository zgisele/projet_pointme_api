<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class qr_tokens extends Model
{
    use HasFactory;

    protected $fillable = ['token', 'created_by', 'valid_until', 'is_active'];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function pointages()
    {
        return $this->hasMany(Pointage::class);
    }


    protected $casts = [
     'is_active' => 'boolean',
     'valid_until' => 'datetime',
    ];



    public function scopeActive($query)
    {
        return $query->where('is_active', true)
                     ->where('valid_until', '>=', now());
    }

    public function isExpired(): bool
    {
        return $this->valid_until === null || $this->valid_until->lt(now());
    }

}

