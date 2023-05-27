<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('billing_address')->nullable();
            $table->enum('type', ['FREE ZONE', 'LOCAL', 'INTERNATIONAL','NOT SPECIFIED'])->default('NOT SPECIFIED');
            $table->foreignId("country_id")->nullable()->constrained("countries")->cascadeOnUpdate();
            $table->string('registration_no')->unique()->nullable();
            $table->json('registration_no_attachment')->nullable();
            $table->string('taxes_no')->unique()->nullable();
            $table->json('taxes_no_attachment')->nullable();
            $table->json('exemption_attachment')->nullable();
            $table->json('sales_taxes_attachment')->nullable();
            $table->string('logo')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vendors');
    }
}
