<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Company extends Model
{
    /**
     * Atributos que podem ser preenchidos em massa.
     */
    protected $fillable = [
        'name',
        'legal_name',
        'document',
        'email',
        'phone',
        'status',
        'timezone',
    ];

    /**
     * Usuários vinculados à empresa.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'company_users')
            ->withPivot([
                'status',
                'joined_at',
            ])
            ->withTimestamps();
    }
}
