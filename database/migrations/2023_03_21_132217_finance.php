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
        Schema::create('finances', function (Blueprint $table) {
            $table->id()->unsigned();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->onUpdate('cascade')->onDelete('cascade')->references('id')->on('users');
            $table->string('name');
            $table->string('color');
            $table->float('percent');
            $table->decimal('balance', 9, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('finances');
    }
};
