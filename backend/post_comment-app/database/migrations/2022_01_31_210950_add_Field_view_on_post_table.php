<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldViewOnPostTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable("posts")) {
            if (!Schema::hasColumn("posts", "views")) {
                Schema::table("posts", function (Blueprint $table) {
                    $table->integer("views")->default(0);
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable("posts")) {
            if (Schema::hasColumn("posts", "views")) {
                Schema::table("posts", function (Blueprint $table) {
                    $table->dropColumn("views");
                });
            }
        }
    }
}
