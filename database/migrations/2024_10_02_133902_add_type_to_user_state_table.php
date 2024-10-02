<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTypeToUserStateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_state', function (Blueprint $table) {
            $table->enum('type', ['permanent', 'temporary'])->default('permanent')->after('state_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_state', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
}
