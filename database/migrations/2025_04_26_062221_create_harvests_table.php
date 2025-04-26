<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHarvestsTable extends Migration
{
    public function up()
    {
        Schema::create('harvests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('farmer_id')->constrained('farmers');
            $table->foreignId('commodity_id')->constrained('commodities');
            $table->enum('grade', ['A', 'B', 'C']);
            $table->decimal('quantity', 10, 2);
            $table->decimal('unit_price', 12, 2);
            $table->decimal('total_amount', 12, 2);
            $table->date('harvest_date');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('harvests');
    }
}
