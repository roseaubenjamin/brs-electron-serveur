<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOptionsTable extends Migration
{

    public function up()
    {
        Schema::create('options', function (Blueprint $table) {
            
            $table->bigIncrements('id');
            $table->string('name') ;
            $table->string('groupe')->nullable() ;
            $table->text('value') ;
            //attachement de cette option a un application ou a un compte mobile crée
            $table->string('options_type')->nullable();
            $table->integer('options_id')->nullable()->unsigned();

            $table->timestamps();
        
        });
    }

    public function down()
    {
        Schema::dropIfExists('options');
    }
}
