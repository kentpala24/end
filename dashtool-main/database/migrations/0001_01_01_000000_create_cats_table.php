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
        Schema::create('cats', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('status')->default(1)->comment('0 delete, 1 active, 2 inactive');
            $table->string('nom', 100)->nullable();
            $table->string('desc', 100)->nullable();
            $table->integer('level')->default(1)->comment('level of cat');
            $table->string('icon', 100)->nullable();
            $table->string('color', 100)->default('info');
            $table->string('slug', 100)->nullable()->comment('slug');
            $table->string('filter_on', 100)->nullable()->comment('where filter');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cats');
    }
};
