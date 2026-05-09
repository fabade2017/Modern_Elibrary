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
        Schema::create('resources', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->text('description')->nullable();
            $table->text('file_path')->nullable();
            $table->unsignedBigInteger('resource_type_id')->index('resources_resource_type_id_foreign');
            $table->unsignedBigInteger('category_id')->index('resources_category_id_foreign');
            $table->boolean('downloadable')->default(false);
            $table->boolean('view_online')->default(true);
            $table->boolean('active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->timestamps();
            $table->integer('class_id')->nullable();
            $table->integer('group_id')->nullable();
            $table->integer('role_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resources');
    }
};
