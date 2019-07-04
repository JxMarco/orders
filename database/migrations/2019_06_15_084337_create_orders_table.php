<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('orderno')->comment('订单号');
            $table->string('cstname')->comment('客户名称');
            $table->string('expressname')->comment('物流公司');
            $table->dateTime('orderdate')->comment('下单日期');
            $table->string('receiver')->comment('收货人');
            $table->string('address')->comment('收货地址');
            $table->string('taobaodesc')->comment('淘宝备注');
            $table->string('sellerdesc')->comment('卖家备注');

            $table->string('procno')->comment('当前工序')->default('1');
            // status: 0-正常  1-暂停  2-撤单
            $table->integer('status')->comment('工单状态')->default('0');
            // isstart: 0-未开工，1-已开工
            $table->boolean('isstart')->comment('是否开工')->default('0');
            $table->boolean('isfinish')->comment('是否已完工')->default('0');
            $table->boolean('isurgent')->comment('是否加急')->default('0');
            
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
        Schema::dropIfExists('orders');
    }
}
