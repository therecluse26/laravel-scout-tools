<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScoutModelsTable extends Migration
{
	public function up()
	{
		Schema::create('scout_models', function (Blueprint $table) {
			$table->id();
			$table->string('model');
			$table->json('indexed_fields')->nullable();
			$table->timestamps();
		});
	}

	public function down()
	{
		// Drop table
		Schema::drop('scout_models');
	}
}
