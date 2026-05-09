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
        Schema::create('school_admin_mappers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('adminemail')->index();
            $table->string('school');
            $table->unsignedInteger('studentcount')->default(0);
            $table->text('others')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('school_admin_mappers');
    }
};
