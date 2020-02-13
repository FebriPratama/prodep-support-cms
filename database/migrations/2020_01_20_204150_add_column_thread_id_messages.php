<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnThreadIdMessages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('tbl_messages', 'thread_id')) {
            Schema::table('tbl_messages', function($table) {     
                $table->string('thread_id')->after('sender_id');
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
        if (!Schema::hasColumn('tbl_messages', 'thread_id')) {
            Schema::table('tbl_messages', function($table) {     
                $table->dropColumn('thread_id');
            });
        }
    }
}
