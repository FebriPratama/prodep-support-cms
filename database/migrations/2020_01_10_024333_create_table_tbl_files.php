<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableTblFiles extends Migration
{

    const TABLE_NAME = 'tbl_files';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::create(static::TABLE_NAME, function (Blueprint $table) {
        //     $table->uuid('id');

        //     $table->primary('id');

        //     $table->string('foreign_id');
        //     $table->string('type');
        //     $table->string('path');

        //     $table->timestamps();
        //     $table->softDeletes();
        // });
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
