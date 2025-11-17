<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Filament\Models\Contracts\{FilamentUser, HasName, HasAvatar};
use Filament\Panel;





class User extends Authenticatable implements JWTSubject,FilamentUser, HasName, HasAvatar
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        
        'first_name',
        'last_name',
        'email',
        'password',
        'photo',
        'phone',
        'promotion',
        'start_date',
        'end_date',
        'role',
        'coach_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Implémentations requises par JWT.
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    //  Méthode que Filament utilise pour afficher le nom de l'utilisateur
    public function getFilamentName(): string
    {
        return trim("{$this->first_name} {$this->last_name}");
    }

    public function getFilamentUserName(): string
    {
        return trim("{$this->first_name} {$this->last_name}");
    }

    //  (Optionnel) Méthode que Filament utilise pour vérifier l’accès admin
    // public function canAccessFilament(): bool
    // {
    //     return $this->role === 'admin';
    // }
    public function canAccessPanel(Panel $panel): bool
    {
        // Seul l'admin a accès
        return $this->role === 'admin';
    }
    public function getFilamentAvatarUrl(): ?string
    {
        return $this->photo ? asset('storage/' . $this->photo) : null;
    }



    
    // Pour les relations coach ↔ stagiaires
    // public function stagiaires()
    // {
    //     return $this->belongsToMany(User::class, 'coach_stagiaire', 'coach_id', 'stagiaire_id','is_active',);
    // }


    // Pour les coachs : liste simple de leurs stagiaires
        public function stagiaires()
    {
        return $this->hasMany(User::class, 'coach_id')->where('role', 'stagiaire');
    }

    // Pour les filtres et la période d'affectation via la table pivot
public function stagiairesPivot()
{
    return $this->belongsToMany(User::class, 'coach_stagiaire', 'coach_id', 'stagiaire_id')
                ->withPivot('date_affectation')
                ->where('role', 'stagiaire');
}

// Pour les stagiaires : tous leurs coachs via pivot
    public function coachs()
    {
        return $this->belongsToMany(User::class, 'coach_stagiaire', 'stagiaire_id', 'coach_id')
        ->withPivot('date_affectation');
    }

    // Pointages du stagiaire
    public function pointages()
    {
        return $this->hasMany(Pointage::class);
    }

    // Sanctions données ou reçues
    public function sanctionsDonnees()
    {
        return $this->hasMany(Sanction::class, 'coach_id');
    }

    public function sanctionsRecues()
    {
        return $this->hasMany(Sanction::class, 'stagiaire_id');
    }

    // QR Tokens créés par l'utilisateur (coach/admin)
    public function qrTokens()
    {
        return $this->hasMany(QrToken::class, 'created_by');
    }

    // Notifications reçues
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }
}
