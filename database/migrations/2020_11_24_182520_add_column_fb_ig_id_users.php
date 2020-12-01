<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnFbIgIdUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('users', 'fb_page_id')) {
            Schema::table('users', function($table) {     
                $table->string('fb_page_id')->nullable();
            });
        }
        if (!Schema::hasColumn('users', 'ig_page_id')) {
            Schema::table('users', function($table) {     
                $table->string('ig_page_id')->nullable();
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
        if (Schema::hasColumn('users', 'fb_page_id')) {
            Schema::table('users', function($table) {     
                $table->dropColumn('fb_page_id');
            });
        }
        if (Schema::hasColumn('users', 'ig_page_id')) {
            Schema::table('users', function($table) {     
                $table->dropColumn('ig_page_id');
            });
        }
    }
}
