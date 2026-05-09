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
        Schema::create('api_import_1756203925', function (Blueprint $table) {
            $table->string('id', 50)->nullable();
            $table->string('name')->nullable();
            $table->string('subdomain')->nullable();
            $table->string('domain')->nullable();
            $table->string('slug')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('api_import_1756203925');
    }
};
