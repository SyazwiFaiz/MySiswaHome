<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Balasan tuan rumah kepada ulasan
        Schema::table('reviews', function (Blueprint $table) {
            $table->text('landlord_reply')->nullable()->after('comment');
            $table->timestamp('replied_at')->nullable()->after('landlord_reply');
        });

        // Rekod sewaan (penyewa semasa / lepas)
        Schema::create('tenancies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('house_id')->constrained()->cascadeOnDelete();
            $table->string('tenant_name');
            $table->string('tenant_phone', 30)->nullable();
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->decimal('monthly_rent', 10, 2);
            $table->decimal('deposit', 10, 2)->default(0);
            $table->text('notes')->nullable();
            $table->string('status', 10)->default('active'); // active | ended
            $table->date('ended_at')->nullable();
            $table->timestamps();
            $table->index(['house_id', 'status']);
        });

        // Bayaran sewa bulanan
        Schema::create('rent_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenancy_id')->constrained()->cascadeOnDelete();
            $table->date('period'); // hari pertama bulan
            $table->decimal('amount', 10, 2);
            $table->date('paid_at');
            $table->string('note')->nullable();
            $table->timestamps();
            $table->unique(['tenancy_id', 'period']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rent_payments');
        Schema::dropIfExists('tenancies');
        Schema::table('reviews', fn (Blueprint $t) => $t->dropColumn(['landlord_reply', 'replied_at']));
    }
};
