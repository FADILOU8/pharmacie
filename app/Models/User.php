<?php

namespace App\Models;

use App\Models\Pharmacy;
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'ai_chatbot_enabled',
        'ai_notifications_enabled',
        'ai_preferences',
    ];

    public const ROLE_PHARMACIEN = 'pharmacien';
    public const ROLE_PREPARATEUR = 'preparateur';
    public const ROLE_CAISSIER = 'caissier';
    public const ROLE_PATIENT = 'patient';

    public static function roles(): array
    {
        return [
            self::ROLE_PHARMACIEN,
            self::ROLE_PREPARATEUR,
            self::ROLE_CAISSIER,
            self::ROLE_PATIENT,
        ];
    }

    public function isPharmacien(): bool
    {
        return $this->role === self::ROLE_PHARMACIEN;
    }

    public function isPreparateur(): bool
    {
        return $this->role === self::ROLE_PREPARATEUR;
    }

    public function isCaissier(): bool
    {
        return $this->role === self::ROLE_CAISSIER;
    }

    public function isPatient(): bool
    {
        return $this->role === self::ROLE_PATIENT;
    }

    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    public function hasAnyRole(array $roles): bool
    {
        return in_array($this->role, $roles, true);
    }

    public function getRoleLabelAttribute(): string
    {
        return match ($this->role) {
            self::ROLE_PHARMACIEN => 'Pharmacien Titulaire',
            self::ROLE_PREPARATEUR => 'Préparateur',
            self::ROLE_CAISSIER => 'Caissier',
            self::ROLE_PATIENT => 'Patient',
            default => 'Utilisateur',
        };
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'ai_preferences' => 'json',
        ];
    }

    public function pharmacy()
    {
        return $this->hasOne(Pharmacy::class, 'user_id');
    }

    public function currentPharmacy(): ?Pharmacy
    {
        if ($this->pharmacy) {
            return $this->pharmacy;
        }

        if ($this->isPreparateur() || $this->isCaissier()) {
            return Pharmacy::first();
        }

        return null;
    }
}
