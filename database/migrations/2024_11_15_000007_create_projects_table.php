<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectsTable extends Migration
{
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->longText('prompt');
            $table->longText('script');
            $table->string('status');
            $table->string('privacy')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}