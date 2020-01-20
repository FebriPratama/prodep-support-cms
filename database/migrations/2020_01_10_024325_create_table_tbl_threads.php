<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableTblThreads extends Migration
{

    const TABLE_NAME = 'tbl_threads';

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

            $table->string('so_id');
            $table->string('pl_id');
            $table->string('cf_id');

            $table->string('customer_id');
            $table->string('cs_id');

            $table->string('thread_status');
            $table->string('thread_reason');

            $table->timestamps();
            // $table->softDeletes();
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
