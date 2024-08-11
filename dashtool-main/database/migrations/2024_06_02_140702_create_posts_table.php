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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('status')->default(1)->comment('0 delete, 1 active, 2 public');
            $table->string('title', 300)->nullable();
            $table->text('content')->nullable();
            $table->string('slug', 300)->nullable();
            $table->string('image', 300)->nullable();
            $table->timestamp('fc')->useCurrent();
            $table->foreignId('user_id');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
