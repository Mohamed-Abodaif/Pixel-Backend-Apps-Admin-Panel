<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpenseTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expense_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->enum('category',[
                'assets',
                'company_operation',
                'client_po',
                'marketing',
                'client_visits_preorders',
                'purchase_for_inventory',
                'taxes',
                'insurances',
                'exchange_currency',
            ]);
            $table->tinyInteger('status')->unsigned()->default(1);
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
        Schema::dropIfExists('expense_types');
    }
}
