<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Tambah column baru
            $table->text('work_experience_message')->nullable()->after('work_experience');
            $table->string('work_experience_file')->nullable()->after('work_experience_message');

            // Optional: kalau nak drop old column
            $table->dropColumn('work_experience');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['work_experience_message', 'work_experience_file']);
        });
    }
};
