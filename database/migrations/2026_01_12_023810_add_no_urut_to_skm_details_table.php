<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('skm_details', function (Blueprint $table) {
            $table->unsignedInteger('no_urut')->nullable()->after('skm_id');
            $table->index(['skm_id', 'no_urut']);
        });
    }

    public function down(): void
    {
        Schema::table('skm_details', function (Blueprint $table) {
            $table->dropIndex(['skm_id', 'no_urut']);
            $table->dropColumn('no_urut');
        });
    }
};
