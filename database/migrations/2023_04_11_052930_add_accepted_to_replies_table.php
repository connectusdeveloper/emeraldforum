<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    public function down(): void
    {
        Schema::table('replies', function (Blueprint $table) {
            $table->dropColumn('accepted');
        });
    }

    public function up(): void
    {
        Schema::table('replies', function (Blueprint $table) {
            $table->boolean('accepted')->nullable()->default('0');
        });
    }
};
