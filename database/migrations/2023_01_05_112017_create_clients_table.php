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
            $table->string('name');
            $table->string('billing_address')->nullable();
            $table->enum('client_type', ['free_zone', 'local', 'international', 'not-specified'])->nullable();
            $table->string('registration_no')->unique()->nullable();
            $table->json('registration_no_attachment')->nullable();
            $table->string('taxes_no')->unique()->nullable();
            $table->json('taxes_no_attachment')->nullable();
            $table->json('exemption_attachment')->nullable();
            $table->json('sales_taxes_attachment')->nullable();
            $table->string('logo')->nullable();
            $table->tinyInteger('status')->unsigned()->default(1);
            $table->string('apartment_number')->nullable();
            $table->string('city')->nullable();
            $table->string('floor')->nullable();
            $table->string('residential_block')->nullable();
            $table->string('site_name')->nullable();
            $table->string('job_role')->nullable();
            $table->string('another_contact_phone')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('contact_name')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('street')->nullable();
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
        Schema::dropIfExists('clients');
    }
}