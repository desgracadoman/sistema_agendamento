<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Cria a tabela de convites empresariais.
     */
    public function up(): void
    {
        Schema::create('company_invitations', function (Blueprint $table) {
            $table->id();

            // Destinatário autorizado.
            $table->string('email');
            $table->string('phone', 20)->nullable();

            // Token armazenado somente em formato hash.
            $table->char('token_hash', 64)->unique();

            // Controle de validade e utilização.
            $table->timestamp('expires_at');
            $table->timestamp('used_at')->nullable();
            $table->timestamp('revoked_at')->nullable();

            // Administrador que criou o convite.
            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // Usuário que utilizou o convite.
            $table->foreignId('used_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

            $table->index('email');
            $table->index('expires_at');
        });
    }

    /**
     * Remove a tabela de convites empresariais.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_invitations');
    }
};