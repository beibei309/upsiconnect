<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1️⃣ Add new columns FIRST
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'work_experience_message')) {
                $table->text('work_experience_message')->nullable();
            }

            if (!Schema::hasColumn('users', 'work_experience_file')) {
                $table->string('work_experience_file')->nullable();
            }
        });

        // 2️⃣ Drop old column in SEPARATE statement
        if (Schema::hasColumn('users', 'work_experience')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('work_experience');
            });
        }
    }

    public function down(): void
    {
        // Restore old column if needed
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'work_experience')) {
                $table->text('work_experience')->nullable();
            }
        });

        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'work_experience_message')) {
                $table->dropColumn('work_experience_message');
            }

            if (Schema::hasColumn('users', 'work_experience_file')) {
                $table->dropColumn('work_experience_file');
            }
        });
    }
};
