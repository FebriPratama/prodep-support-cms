<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnSoNoSalesorders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('tbl_salesorders', 'salesorder_no')) {
            Schema::table('tbl_salesorders', function($table) {     
                $table->string('salesorder_no')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (!Schema::hasColumn('tbl_salesorders', 'salesorder_no')) {
            Schema::table('tbl_salesorders', function($table) {     
                $table->dropColumn('salesorder_no');
            });
        }
    }
}
