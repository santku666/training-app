<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('otp_logs',function(Blueprint $table){
            $table->id();
            $table->string('mode',50);
            $table->integer('code',false,true)->sizeof(6);
            $table->bigInteger('user_id',false,true);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamp('expired_at');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('otp_logs');
    }
};
