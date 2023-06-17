<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSitesInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        Schema::create('sites_info', function (Blueprint $table) {
//            $table->id();
//            $table->unsignedBigInteger('client_site_id')->nullable();
//            $table->foreign('client_site_id')->references('id')->on('client_sites')->onUpdate('cascade')->onDelete('cascade');
//            $table->string('contact_name');
//            $table->string('job_role');
//            $table->string('contact_email');
//            $table->string('contact_phone');
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
        Schema::dropIfExists('sites_info');
    }
}
