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
        Schema::create('modules', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('status')->default(1)->comment('0 delete, 1 active, 2 inactive');
            $table->string('type', 30)->default('module')->comment('type module');
            $table->string('nom', 100)->nullable();
            $table->string('desc', 100)->nullable();
            $table->string('icon', 100)->nullable();
            $table->string('url_module', 300)->nullable()->comment('url main uses for dashboard');
            $table->string('color', 100)->default('info');
            $table->string('url_sub_module', 100)->nullable()->comment('url by access to module');
            $table->string('show_on', 100)->nullable()->comment('where show module');
            $table->foreignId('back_module_id')->nullable()->comment('id module back');
            $table->foreignId('module_id')->nullable()->comment('id parent module depend');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modules');
    }
};
