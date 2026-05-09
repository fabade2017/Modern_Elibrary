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
        Schema::create('cdssbon', function (Blueprint $table) {
            $table->string('id', 50)->nullable();
            $table->string('admission_number')->nullable();
            $table->string('surname')->nullable();
            $table->string('firstname')->nullable();
            $table->string('othername')->nullable();
            $table->string('class')->nullable();
            $table->string('arm')->nullable();
            $table->string('department')->nullable();
            $table->string('category')->nullable();
            $table->string('date_of_birth')->nullable();
            $table->string('email')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cdssbon');
    }
};
