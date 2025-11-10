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
        Schema::table('reviews', function (Blueprint $table) {
            // Make conversation_id nullable since reviews can be for service_requests or service_applications
            $table->foreignId('conversation_id')->nullable()->change();
            
            // Add service_request_id if not exists
            if (!Schema::hasColumn('reviews', 'service_request_id')) {
                $table->foreignId('service_request_id')->nullable()->after('conversation_id')->constrained('service_requests')->cascadeOnDelete();
            }
            
            // Add service_application_id if not exists
            if (!Schema::hasColumn('reviews', 'service_application_id')) {
                $table->foreignId('service_application_id')->nullable()->after('service_request_id')->constrained('service_applications')->cascadeOnDelete();
            }
            
            // Add is_follow_up column if not exists
            if (!Schema::hasColumn('reviews', 'is_follow_up')) {
                $table->boolean('is_follow_up')->default(false)->after('comment');
            }
        });
        
        // Handle unique constraint separately - try to drop old and add new
        try {
            Schema::table('reviews', function (Blueprint $table) {
                $table->dropUnique(['conversation_id', 'reviewer_id', 'reviewee_id']);
            });
        } catch (\Exception $e) {
            // Old constraint may not exist if migrated from SQL
        }
        
        try {
            Schema::table('reviews', function (Blueprint $table) {
                $table->unique(['conversation_id', 'reviewer_id', 'reviewee_id', 'is_follow_up'], 'reviews_conversation_reviewer_reviewee_followup_unique');
            });
        } catch (\Exception $e) {
            // New constraint may already exist from SQL import
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropUnique('reviews_conversation_reviewer_reviewee_followup_unique');
            $table->unique(['conversation_id', 'reviewer_id', 'reviewee_id']);
            
            $table->dropColumn(['service_request_id', 'service_application_id', 'is_follow_up']);
            
            $table->foreignId('conversation_id')->nullable(false)->change();
        });
    }
};
