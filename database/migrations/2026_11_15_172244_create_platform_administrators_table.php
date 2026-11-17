<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Cria a tabela de administradores gerais da plataforma.
     */
    public function up(): void
    {
        Schema::create('platform_administrators', function (Blueprint $table) {
            $table->id();

            // Usuário autorizado como administrador geral.
            $table->foreignId('user_id')
                ->unique()
                ->constrained('users')
                ->cascadeOnDelete();

            // Administrador que concedeu a autorização.
            // NULL permite cadastrar o primeiro administrador.
            $table->foreignId('granted_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // Situação da autorização.
            $table->string('status', 20)->default('active');

            // Datas de concessão e revogação.
            $table->timestamp('granted_at');
            $table->timestamp('revoked_at')->nullable();

            $table->timestamps();

            $table->index('status');
        });
    }

    /**
     * Remove a tabela de administradores gerais.
     */
    public function down(): void
    {
        Schema::dropIfExists('platform_administrators');
    }
};