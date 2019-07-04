<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderfollowTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orderfollow', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('orderid')->comment('工单ID');
            $table->integer('userid')->comment('用户ID');
            $table->string('typename')->comment('业务类型');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orderfollow');
    }
}
