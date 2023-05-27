<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('company_id')->nullable();
            $table->string('name');
            $table->string('company_sector');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('logo')->nullable();
            $table->unsignedBigInteger('country_id');
            $table->foreign('country_id')->references('id')->on('countries')->onUpdate('cascade')->onDelete('cascade');
            $table->string('employees_no');
            $table->string('branches_no');
            $table->enum('package_status', ['Basic', 'Upgraded-no-Due', 'Upgraded-in-Due', 'Upgraded-over-Due'])->nullable();
            $table->boolean('is_active')->default(0);
            $table->foreignId('package_id')->nullable();
            $table->text('dates')->nullable();
            $table->string('admin_email')->unique();
            $table->string('billing_address')->nullable();
            $table->enum('company_tax_type', ['free_zone_client', 'local_client', 'international_client'])->nullable();
            $table->enum(
                'registration_status',
                ['pending', 'approved', 'rejected']
            )->default('pending');
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
        Schema::dropIfExists('companies');
    }
}
