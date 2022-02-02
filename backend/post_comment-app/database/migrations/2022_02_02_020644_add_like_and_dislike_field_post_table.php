<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLikeAndDislikeFieldPostTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable("posts")) {
            if (!Schema::hasColumn("posts", "like")) {
                Schema::table("posts", function (Blueprint $table) {
                    $table->integer("like")->default(0);
                });
            }

            if (!Schema::hasColumn("posts", "dislike")) {
                Schema::table("posts", function (Blueprint $table) {
                    $table->integer("dislike")->default(0);
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
        if (Schema::hasColumn("posts", "like")) {
            Schema::table("posts", function (Blueprint $table) {
                $table->dropColumn("like");
            });
        }

        if (Schema::hasColumn("posts", "dislike")) {
            Schema::table("posts", function (Blueprint $table) {
                $table->dropColumn("dislike");
            });
        }
    }
}
