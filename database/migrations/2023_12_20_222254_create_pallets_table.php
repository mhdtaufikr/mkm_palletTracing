<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePalletsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pallets', function (Blueprint $table) {
            $table->id();
            $table->string('no_delivery');
            $table->string('no_pallet');
            $table->string('type_pallet');
            $table->string('destination');
            $table->date('date');
            $table->integer('status'); // New column
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pallets');
    }
}
