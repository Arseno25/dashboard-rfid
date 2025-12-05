<?php

use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
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
            $table->string('order_number')->nullable()->unique()->after('id');
            $table->foreignIdFor(Customer::class)->nullable()->change();
            $table->foreignIdFor(Product::class)->nullable()->change();
            $table->string('buyer_name')->nullable()->after('customer_id');
            $table->string('buyer_email')->nullable()->after('buyer_name');
            $table->string('buyer_phone')->nullable()->after('buyer_email');
            $table->string('rfid_uid')->nullable()->after('buyer_phone');
            $table->string('payment_method')->default('rfid')->after('status');
            $table->string('payment_status')->default('paid')->after('payment_method');
            $table->string('gateway_reference')->nullable()->after('payment_status');
            $table->json('gateway_payload')->nullable()->after('gateway_reference');
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Order::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Product::class)->nullable()->constrained()->nullOnDelete();
            $table->string('product_name');
            $table->integer('price');
            $table->integer('quantity');
            $table->integer('subtotal');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'order_number',
                'buyer_name',
                'buyer_email',
                'buyer_phone',
                'rfid_uid',
                'payment_method',
                'payment_status',
                'gateway_reference',
                'gateway_payload',
            ]);

            $table->foreignIdFor(Customer::class)->nullable(false)->change();
            $table->foreignIdFor(Product::class)->nullable(false)->change();
        });
    }
};
