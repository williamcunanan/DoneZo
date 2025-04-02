<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::table('tasks', function (Blueprint $table) {
            $table->foreignId('user_id')->after('id')->nullable()->constrained()->onDelete('cascade');
        });

        // Update existing tasks to belong to the first user (if any exists)
        if (Schema::hasTable('users')) {
            $firstUserId = \App\Models\User::first()?->id;
            if ($firstUserId) {
                \App\Models\Task::whereNull('user_id')->update(['user_id' => $firstUserId]);
            }
        }

        // Make user_id required after updating existing records
        Schema::table('tasks', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable(false)->change();
        });
    }
    public function down() {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
}; 