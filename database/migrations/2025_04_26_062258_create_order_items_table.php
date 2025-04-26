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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();

            // foreignId() otomatis unsignedBigInteger & index
            $table->foreignId('order_id')
                ->constrained('orders')      // refer ke orders.id
                ->cascadeOnDelete();         // ON DELETE CASCADE

            $table->foreignId('commodity_id')
                ->constrained('commodities'); // refer ke commodities.id

            $table->enum('grade', ['A', 'B', 'C']);
            $table->decimal('quantity', 10, 2);
            $table->decimal('price', 12, 2);
            $table->decimal('total_price', 12, 2);

            $table->timestamps();

            // Unique constraint: mencegah duplikat komoditas + grade pada satu order
            $table->unique(
                ['order_id', 'commodity_id', 'grade'],
                'order_item_unique'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
