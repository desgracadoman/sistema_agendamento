<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class CompanyInvitation extends Model
{
    protected $fillable = [
        'company_name',
        'email',
        'phone',
        'token_hash',
        'expires_at',
        'used_at',
        'revoked_at',
        'created_by',
        'used_by',
    ];

    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
            'used_at' => 'datetime',
            'revoked_at' => 'datetime',
        ];
    }

    /**
     * Administrador que criou o convite.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Usuário que utilizou o convite.
     */
    public function usedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'used_by');
    }

    /**
     * Solicitação empresarial originada pelo convite.
     */
    public function registrationRequest(): HasOne
    {
        return $this->hasOne(CompanyRegistrationRequest::class, 'invitation_id');
    }

    /**
     * Verifica se o convite ainda está disponível para utilização.
     */
    public function isAvailable(): bool
    {
        return $this->used_at === null
            && $this->revoked_at === null
            && $this->expires_at !== null
            && $this->expires_at->isFuture();
    }
}
