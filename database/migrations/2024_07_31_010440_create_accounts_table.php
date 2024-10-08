<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->uuid()->primary();
            $table->string('name');
            $table->foreignId('owner_user_id')->constrained('users');

            $table->unsignedInteger('config_space_limit_mb')->default(500);
            $table->unsignedInteger('config_space_limit_mb_used')->default(0);
            $table->unsignedInteger('config_user_limit')->default(2);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        User::query()->update(['account_uuid' => null]);
        Schema::dropIfExists('accounts');
    }
};
