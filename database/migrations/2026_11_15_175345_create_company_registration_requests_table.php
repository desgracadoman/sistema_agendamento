<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Cria a tabela de solicitações de cadastro empresarial.
     */
    public function up(): void
    {
        Schema::create('company_registration_requests', function (Blueprint $table) {
            $table->id();

            // Origem da solicitação.
            $table->foreignId('invitation_id')
                ->unique()
                ->constrained('company_invitations')
                ->restrictOnDelete();

            // Usuário responsável pela solicitação.
            $table->foreignId('user_id')
                ->constrained('users')
                ->restrictOnDelete();

            // Dados cadastrais da empresa.
            $table->string('name');
            $table->string('legal_name')->nullable();
            $table->string('document', 14)->nullable();
            $table->string('email');
            $table->string('phone', 20)->nullable();

            // Configuração regional.
            $table->string('timezone', 64)->default('America/Sao_Paulo');

            // Controle da análise administrativa.
            $table->string('status', 20)->default('pending');

            $table->foreignId('reviewed_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('reviewed_at')->nullable();
            $table->text('rejection_reason')->nullable();

            // Preenchido somente depois da aprovação.
            $table->foreignId('company_id')
                ->nullable()
                ->unique()
                ->constrained('companies')
                ->nullOnDelete();

            $table->timestamps();

            $table->index('status');
            $table->index('document');
            $table->index('user_id');
        });
    }

    /**
     * Remove a tabela de solicitações.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_registration_requests');
    }
};