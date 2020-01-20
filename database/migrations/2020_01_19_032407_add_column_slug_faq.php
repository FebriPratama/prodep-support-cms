<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnSlugFaq extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('tbl_faqs', 'slug')) {
            Schema::table('tbl_faqs', function($table) {     
                $table->string('slug')->nullable();
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
        if (!Schema::hasColumn('tbl_faqs', 'slug')) {
            Schema::table('tbl_faqs', function($table) {     
                $table->dropColumn('slug');
            });
        }
    }
}
