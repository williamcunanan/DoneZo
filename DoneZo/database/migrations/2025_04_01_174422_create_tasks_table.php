<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('category')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->boolean('completed')->default(false);
            $table->timestamps();
        });
    }
    public function down() {
        Schema::dropIfExists('tasks');
    }
};
