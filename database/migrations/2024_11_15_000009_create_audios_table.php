<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAudiosTable extends Migration
{
    public function up()
    {
        Schema::create('audios', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('audio_url')->nullable();
            $table->boolean('completed')->default(0)->nullable();
            $table->integer('token')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
