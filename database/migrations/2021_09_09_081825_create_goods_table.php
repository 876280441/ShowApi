<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGoodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goods', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->comment('创建者');
            $table->string('title')->comment('商品标题');
            $table->integer('category_id')->comment('分类id');
            $table->string('description')->comment('描述');
            $table->integer('price')->comment('价格');
            $table->integer('stock')->comment('库存');
            $table->string('cover')->comment('封面图');
            $table->json('pics')->comment('小图集');
            $table->tinyInteger('is_on')->default(0)->comment('是否上架 0不上 1上');
            $table->tinyInteger('is_recommend')->default(0)->comment('是否显示到推荐位 0不显示 1显示');
            $table->text('details')->comment('详情');
            $table->timestamps();
            $table->index('category_id');
            $table->index('title');
            $table->index('is_on');
            $table->index('is_recommend');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('goods');
    }
}
