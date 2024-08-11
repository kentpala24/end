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
        Schema::create('permits', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('status')->default(1)->comment('0 inactive, 1 active');
            $table->tinyInteger('level')->default(0)->comment('Level permits (1-3)');
            $table->string('url_module', 60)->nullable()->comment('Url sub module');
            
            $table->foreignId('module_id')->nullable()->comment('id module parent sub module');
            $table->foreign('module_id')->references('id')->on('modules');

            $table->foreignId('sub_module_id');
            $table->foreign('sub_module_id')->references('id')->on('modules');

            $table->foreignId('user_id');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permits');
    }
};
