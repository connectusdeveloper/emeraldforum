<?php

use App\Models\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->dropForeignIdFor(User::class);
        });
    }

    public function up(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->foreignIdFor(User::class)->nullable();
        });
    }
};
