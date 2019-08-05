<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableShorturl extends Migration
{
    
    public function up()
    {
        Schema::create('Sorturls', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('url');
            $table->string('action_type');
            $table->string('code')->unique();
            $table->date('expire') ; 
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('Sorturls');
    }
}
