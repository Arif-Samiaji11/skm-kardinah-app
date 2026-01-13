<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        /*
        |----------------------------------------------------------
        | USERS
        |----------------------------------------------------------
        | - admin     : buat pertanyaan
        | - ruangan   : isi survei
        |
        | NOTE:
        | - unit_ruangan_id dibuat dulu TANPA FK (biar migrate:fresh aman).
        | - FK akan ditambahkan di migration TERPISAH setelah unit_ruangan ada.
        */
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            // IDENTITAS
            $table->string('name');
            $table->string('email')->unique();

            // ROLE
            $table->enum('role', ['admin', 'ruangan'])->default('ruangan');

            // INFO RUANGAN (lama - optional)
            $table->string('ruangan')->nullable();
            $table->year('tahun')->default(2026);

            // INFO RUANGAN (baru - relasi)
            $table->unsignedBigInteger('unit_ruangan_id')->nullable()->index();

            // AUTH
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();

            $table->timestamps();
        });

        /*
        |----------------------------------------------------------
        | PASSWORD RESET TOKENS
        |----------------------------------------------------------
        */
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        /*
        |----------------------------------------------------------
        | SESSIONS
        |----------------------------------------------------------
        */
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();

            $table->foreignId('user_id')
                ->nullable()
                ->index()
                ->constrained('users')
                ->nullOnDelete();

            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
    }
};
