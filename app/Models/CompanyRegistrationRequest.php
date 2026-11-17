<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompanyRegistrationRequest extends Model
{
    public const STATUS_PENDING = 'pending';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECTED = 'rejected';

    protected $fillable = [
        'invitation_id',
        'user_id',
        'name',
        'legal_name',
        'document',
        'email',
        'phone',
        'timezone',
        'status',
        'reviewed_by',
        'reviewed_at',
        'rejection_reason',
        'company_id',
    ];

    protected function casts(): array
    {
        return [
            'reviewed_at' => 'datetime',
        ];
    }

    /**
     * Convite utilizado para realizar a solicitação.
     */
    public function invitation(): BelongsTo
    {
        return $this->belongsTo(CompanyInvitation::class, 'invitation_id');
    }

    /**
     * Usuário responsável pela solicitação.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Administrador geral responsável pela análise.
     */
    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    /**
     * Empresa criada após a aprovação.
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Verifica se a solicitação está aguardando análise.
     */
    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * Verifica se a solicitação foi aprovada.
     */
    public function isApproved(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }

    /**
     * Verifica se a solicitação foi rejeitada.
     */
    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }
}