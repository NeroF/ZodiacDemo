<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatZodiacTable extends Migration
{
/**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('zodiac', function (Blueprint $table) {
            $table->increments('id')->comment('ID');
            $table->string('zodiac')->comment('星座');
            $table->unsignedInteger('total_score')->comment('總分');
            $table->string('total_comment')->comment('總評');
            $table->unsignedInteger('love_score')->comment('愛情');
            $table->string('love_comment')->comment('愛情評論');
            $table->unsignedInteger('business_score')->comment('工作');
            $table->string('business_comment')->comment('工作評論');
            $table->unsignedInteger('fortune_score')->comment('幸運');
            $table->string('fortune_comment')->comment('幸運評論');
            $table->timestamp('created_at')->useCurrent()->comment('建立時間');
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'))->comment('更新時間');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('zodiac');
    }
}
