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
        Schema::table('role_privilege', function (Blueprint $table) {
            $table->foreign(['privilege_id'])->references(['id'])->on('privileges')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['role_id'])->references(['id'])->on('roles')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('role_privilege', function (Blueprint $table) {
            $table->dropForeign('role_privilege_privilege_id_foreign');
            $table->dropForeign('role_privilege_role_id_foreign');
        });
    }
};
