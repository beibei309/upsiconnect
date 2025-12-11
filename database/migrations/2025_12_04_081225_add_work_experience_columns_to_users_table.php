<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Pastikan column work_experience wujud sebelum guna after() atau drop
            if (Schema::hasColumn('users', 'work_experience')) {
                $table->text('work_experience_message')->nullable()->after('work_experience');
            } else {
                // Jika tak wujud, letak saja kat hujung
                $table->text('work_experience_message')->nullable();
            }

            $table->string('work_experience_file')->nullable();

            // Drop kalau wujud
            if (Schema::hasColumn('users', 'work_experience')) {
                $table->dropColumn('work_experience');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'work_experience_message')) {
                $table->dropColumn('work_experience_message');
            }

            if (Schema::hasColumn('users', 'work_experience_file')) {
                $table->dropColumn('work_experience_file');
            }

            // Optional: return old column
            // $table->text('work_experience')->nullable();
        });
    }
};
