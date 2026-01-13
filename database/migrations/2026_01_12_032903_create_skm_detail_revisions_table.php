<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('skm_detail_revisions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('skm_detail_id');

            // snapshot nilai sebelum update
            $table->unsignedTinyInteger('u1');
            $table->unsignedTinyInteger('u2');
            $table->unsignedTinyInteger('u3');
            $table->unsignedTinyInteger('u4');
            $table->unsignedTinyInteger('u5');
            $table->unsignedTinyInteger('u6');
            $table->unsignedTinyInteger('u7');
            $table->unsignedTinyInteger('u8');
            $table->unsignedTinyInteger('u9');

            $table->timestamps();

            $table->foreign('skm_detail_id')
                ->references('id')->on('skm_details')
                ->onDelete('cascade');

            $table->index(['skm_detail_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('skm_detail_revisions');
    }
};
