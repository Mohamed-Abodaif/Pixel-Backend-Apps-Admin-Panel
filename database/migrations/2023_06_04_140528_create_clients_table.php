<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->uuid("hashed_id");
            $table->string('name');
            $table->enum('client_type', ['individual', 'free_zone', 'local', 'international']);
            $table->string('logo')->nullable();
            $table->enum('status', ['active', 'inactive', 'blocked']);
            $table->string('registration_no')->unique()->nullable();
            $table->json('registration_no_attachment')->nullable();
            $table->string('taxes_no')->unique()->nullable();
            $table->json('taxes_no_attachment')->nullable();
            $table->json('exemption_attachment')->nullable();
            $table->json('sales_taxes_attachment')->nullable();

            $table->softDeletes();
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
        Schema::dropIfExists('clients');
    }
}
