<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientSitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        Schema::create('client_sites', function (Blueprint $table) {
//            $table->id();
//            $table->unsignedBigInteger('client_id')->nullable();
//            $table->foreign('client_id')->references('id')->on('clients')->onUpdate('cascade')->onDelete('cascade');
//            $table->string('name');
//            $table->timestamp('created_at')->useCurrent();
//            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
//            $table->softDeletes();
//        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('client_sites');
    }
}
