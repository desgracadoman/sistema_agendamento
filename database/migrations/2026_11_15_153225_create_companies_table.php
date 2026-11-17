
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();

            // Identificação
            $table->string('name');
            $table->string('legal_name')->nullable();
            $table->string('document', 14)->nullable()->unique();

            // Contato
            $table->string('email')->nullable();
            $table->string('phone', 20)->nullable();

            // Configurações
            $table->string('status', 20)->default('active');
            $table->string('timezone', 64)->default('America/Sao_Paulo');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
