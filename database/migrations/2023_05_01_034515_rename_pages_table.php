<?php

use App\Models\Setting;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    public function up(): void
    {
        Schema::rename('pages', 'articles');
        Setting::where('tec_key', 'pages')->update(['tec_key' => 'articles']);
    }
};
