<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAvatarsTable extends Migration
{
    public function up()
    {
        Schema::create('avatars', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->longText('prompt')->nullable();
            $table->string('avatar_url')->nullable();
            $table->string('avatar')->nullable();
            $table->boolean('completed')->default(0)->nullable();
            $table->integer('token')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
