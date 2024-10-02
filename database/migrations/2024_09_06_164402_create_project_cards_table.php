<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('project_cards', function (Blueprint $table) {
            $table->uuid()->primary();
            $table->foreignId('created_by_user_id')
                ->constrained('users')
                ->onDelete('cascade');
            $table->foreignUuid('project_uuid')
                ->constrained('projects', 'uuid')
                ->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->string('ulid_deletion', 26)
                ->nullable()
                ->default(null)
                ->comment('Unique local identifier for deletion');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_cards');
    }
};
