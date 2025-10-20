<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sanctions extends Model
{
    use HasFactory;
    protected $fillable = ['coach_id', 'stagiaire_id', 'motif', 'description', 'niveau', 'date_sanction'];

    public function coach()
    {
        return $this->belongsTo(User::class, 'coach_id');
    }

    public function stagiaire()
    {
        return $this->belongsTo(User::class, 'stagiaire_id');
    }
}
