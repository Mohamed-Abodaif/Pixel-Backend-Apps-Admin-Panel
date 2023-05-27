<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeStatusDefaultActiveInSystemConfig extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('taxes_types', function (Blueprint $table) {
            //
            $table->boolean('status')->default(1)->change();
        });
        Schema::table('payment_methods', function (Blueprint $table) {
            //
            $table->boolean('status')->default(1)->change();
        });
        Schema::table('payment_terms', function (Blueprint $table) {
            //
            $table->boolean('status')->default(1)->change();
        });
        Schema::table('assets_categories', function (Blueprint $table) {
            //
            $table->boolean('status')->default(1)->change();
        });


        Schema::table('custody_senders', function (Blueprint $table) {
            //
            $table->boolean('status')->default(1)->change();
        });
        Schema::table('departments', function (Blueprint $table) {
            //
            $table->boolean('status')->default(1)->change();
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
