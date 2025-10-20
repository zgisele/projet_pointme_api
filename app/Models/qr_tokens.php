<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
