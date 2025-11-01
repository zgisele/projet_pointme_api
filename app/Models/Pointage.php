<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pointage extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'qr_token_id', 'statut', 'heure_arrivee', 'heure_sortie', 'note', 'date_pointage'
    ];

    // Le stagiaire lié
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function stagiaire()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    // Le QR token lié
    public function qrToken()
    {
        return $this->belongsTo(QrToken::class);
    }
    // public function qrToken()
    // {
    //     return $this->belongsTo(QrToken::class, 'qr_token_id');
    // }

    

    
}
