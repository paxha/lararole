<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateModulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('modules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('module_id')->nullable();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->string('alias');
            $table->integer('order')->nullable();

            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('module_id')->references('id')->on('modules')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('no action');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('no action');
            $table->foreign('deleted_by')->references('id')->on('users')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('modules');
    }
}
