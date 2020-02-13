<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableTblSalesorders extends Migration
{

    const TABLE_NAME = 'tbl_salesorders';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    { 
        Schema::create(static::TABLE_NAME, function (Blueprint $table) {
            $table->uuid('id');
            $table->primary('id');
            
            $table->string('so_type');
            $table->string('so_product_name');
            $table->decimal('so_total',19,4);            
            $table->string('user_id');
            $table->string('so_status');
            $table->timestamps();
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
        Schema::dropIfExists(static::TABLE_NAME);
    }
}
