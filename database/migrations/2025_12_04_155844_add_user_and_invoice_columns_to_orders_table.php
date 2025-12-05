<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('customer_id')->constrained()->nullOnDelete();
            $table->string('buyer_note', 255)->nullable()->after('buyer_phone');
            $table->string('invoice_path')->nullable()->after('gateway_payload');
            $table->timestamp('invoice_sent_at')->nullable()->after('invoice_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn(['user_id', 'buyer_note', 'invoice_path', 'invoice_sent_at']);
        });
    }
};
