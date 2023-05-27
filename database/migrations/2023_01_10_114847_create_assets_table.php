<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->string('asset_name');
            $table->mediumText('asset_description')->nullable();
            $table->string('picture')->nullable();
            $table->date('buying_date');

            $table->foreignId("department_id")->constrained("departments")->cascadeOnUpdate();
            $table->foreignId("asset_category_id")->constrained("assets_categories")->cascadeOnUpdate();

            $table->json('asset_documents')->nullable();
            $table->mediumText('notes')->nullable();
            $table->enum('status',['Available','In Maintenance'])->default('Available');
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
        Schema::dropIfExists('assets');
    }
}
