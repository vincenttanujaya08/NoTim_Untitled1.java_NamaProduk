<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommodityStocksTable extends Migration
{
    public function up()
    {
        Schema::create('commodity_stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('commodity_id')->constrained('commodities');
            $table->enum('grade', ['A', 'B', 'C']);
            $table->decimal('quantity', 10, 2)->default(0);
            $table->timestamps();
            $table->unique(['commodity_id', 'grade']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('commodity_stocks');
    }
}
