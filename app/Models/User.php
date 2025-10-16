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
}
