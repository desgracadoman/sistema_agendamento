<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Adiciona o nome sugerido da empresa aos convites.
     */
    public function up(): void
    {
        Schema::table('company_invitations', function (Blueprint $table) {
            $table->string('company_name')
                ->nullable()
                ->after('email');
        });
    }

    /**
     * Remove o nome sugerido da empresa.
     */
    public function down(): void
    {
        Schema::table('company_invitations', function (Blueprint $table) {
            $table->dropColumn('company_name');
        });
    }
};
