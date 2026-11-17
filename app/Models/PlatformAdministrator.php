<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlatformAdministrator extends Model
{
    /**
     * Situações possíveis da autorização administrativa.
     */
    public const STATUS_ACTIVE = 'active';

    public const STATUS_REVOKED = 'revoked';

    /**
     * Campos que podem ser preenchidos em operações controladas.
     */
    protected $fillable = [
        'user_id',
        'granted_by',
        'status',
        'granted_at',
        'revoked_at',
    ];

    /**
     * Conversões automáticas dos atributos.
     */
    protected function casts(): array
    {
        return [
            'granted_at' => 'datetime',
            'revoked_at' => 'datetime',
        ];
    }

    /**
     * Usuário que recebeu a autorização administrativa.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Usuário que concedeu a autorização administrativa.
     */
    public function grantedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'granted_by');
    }

    /**
     * Verifica se a autorização está ativa.
     */
    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }
}