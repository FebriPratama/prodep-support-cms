<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnDescriptionProblemList extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('tbl_problem_lists', 'description')) {
            Schema::table('tbl_problem_lists', function($table) {     
                $table->text('description')->nullable();
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
        if (!Schema::hasColumn('tbl_problem_lists', 'description')) {
            Schema::table('tbl_problem_lists', function($table) {     
                $table->dropColumn('description');
            });
        }
    }
}
