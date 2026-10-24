<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Soft delete untuk rumah (boleh dipulihkan dari admin)
        Schema::table('houses', function (Blueprint $table) {
            $table->softDeletes();
        });

        // Gantung / aktifkan akaun tanpa memadam data
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_active')->default(true)->after('role');
        });

        // Jejak audit semua tindakan admin
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('action', 40);
            $table->string('subject_type')->nullable();
            $table->unsignedBigInteger('subject_id')->nullable();
            $table->string('description');
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();
            $table->index(['subject_type', 'subject_id']);
            $table->index('action');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
        Schema::table('users', fn (Blueprint $t) => $t->dropColumn('is_active'));
        Schema::table('houses', fn (Blueprint $t) => $t->dropSoftDeletes());
    }
};
