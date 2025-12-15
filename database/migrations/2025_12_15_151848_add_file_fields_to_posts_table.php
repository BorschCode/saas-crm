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
        Schema::table('posts', function (Blueprint $table) {
            $table->foreignId('source_file_id')->nullable();
            $table->longText('original_content')->nullable();
            $table->string('guest_session_id')->nullable();
            $table->string('parsing_state')->nullable();
            $table->text('parsing_error')->nullable();
            $table->timestamp('parsing_completed_at')->nullable();

            $table->index('source_file_id');
            $table->index('guest_session_id');
            $table->index('parsing_state');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropIndex(['source_file_id']);
            $table->dropIndex(['guest_session_id']);
            $table->dropIndex(['parsing_state']);

            $table->dropForeign(['source_file_id']);
            $table->dropColumn([
                'source_file_id',
                'original_content',
                'guest_session_id',
                'parsing_state',
                'parsing_error',
                'parsing_completed_at',
            ]);
        });
    }
};
