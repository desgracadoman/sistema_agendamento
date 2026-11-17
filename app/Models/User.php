<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * Empresas às quais o usuário está vinculado.
     */
    public function companies(): BelongsToMany
    {
        return $this->belongsToMany(Company::class, 'company_users')
            ->withPivot([
                'status',
                'joined_at',
            ])
            ->withTimestamps();
    }

    /**
     * Autorização administrativa geral do usuário.
     */
    public function platformAdministrator(): HasOne
    {
        return $this->hasOne(PlatformAdministrator::class);
    }

    /**
     * Autorizações administrativas concedidas pelo usuário.
     */
    public function grantedPlatformAdministrations(): HasMany
    {
        return $this->hasMany(
            PlatformAdministrator::class,
            'granted_by'
        );
    }

    /**
     * Verifica se o usuário possui autorização administrativa ativa.
     */
    public function isPlatformAdministrator(): bool
    {
        return $this->platformAdministrator()
            ->where('status', PlatformAdministrator::STATUS_ACTIVE)
            ->exists();
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
