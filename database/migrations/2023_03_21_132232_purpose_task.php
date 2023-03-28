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
        Schema::create('purpose_tasks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('purpose_id')->nullable();
            $table->foreign('purpose_id')->onUpdate('cascade')->onDelete('cascade')->references('id')->on('purposes');
            $table->string('name');
            $table->boolean('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purpose_tasks');
    }
};
