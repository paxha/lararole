<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateModuleRoleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('module_role', function (Blueprint $table) {
            $table->id('id');
            $table->unsignedBigInteger('role_id');
            $table->unsignedBigInteger('module_id');
            $table->enum('permission', ['read', 'write'])->default('read');
            $table->timestamps();

            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
            $table->foreign('module_id')->references('id')->on('modules')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('module_role');
    }
}
