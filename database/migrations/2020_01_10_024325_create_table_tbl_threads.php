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

            $table->uuid('so_id');
            $table->uuid('pl_id');
            $table->uuid('cf_id');

            $table->uuid('customer_id');
            $table->uuid('cs_id');

            $table->string('thread_status');
            $table->string('thread_reason')->nullable();

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
