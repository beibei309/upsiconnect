<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('student_services', function (Blueprint $table) {
            // Basic package
            $table->integer('basic_duration')->nullable();
            $table->string('basic_frequency')->nullable();
            $table->decimal('basic_price', 10, 2)->nullable();
            $table->text('basic_description')->nullable();

            // Standard package
            $table->integer('standard_duration')->nullable();
            $table->string('standard_frequency')->nullable();
            $table->decimal('standard_price', 10, 2)->nullable();
            $table->text('standard_description')->nullable();

            // Premium package
            $table->integer('premium_duration')->nullable();
            $table->string('premium_frequency')->nullable();
            $table->decimal('premium_price', 10, 2)->nullable();
            $table->text('premium_description')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('student_services', function (Blueprint $table) {
            $table->dropColumn([
                'basic_duration', 'basic_frequency', 'basic_price', 'basic_description',
                'standard_duration', 'standard_frequency', 'standard_price', 'standard_description',
                'premium_duration', 'premium_frequency', 'premium_price', 'premium_description',
            ]);
        });
    }
};
