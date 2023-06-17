<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientQuotationItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_quotation_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_quotation_id');
            $table->foreign('client_quotation_id')->references('id')->on('client_quotations')->onUpdate('cascade')->onDelete('cascade');
            $table->integer('quantity_value');
            $table->integer('quantity_option');
            $table->mediumText('description');
            $table->float('unit_price');
            $table->float('total_price');
            $table->string('image')->nullable();
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
        Schema::dropIfExists('client_quotation_items');
    }
}
