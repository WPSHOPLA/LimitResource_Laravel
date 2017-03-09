<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWineTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('wines', function($table) {
            $table->increments('id');

            $table->integer('vineyard_id')->unsigned()->index()->nullable();
			$table->foreign('vineyard_id')->references('id')->on('vineyards')->onDelete('cascade');
			$table->integer('user_id')->unsigned()->index()->nullable();
			$table->foreign('user_id')->references('id')->on('users')->onDelete(DB::raw('set null'));

            $table->string('name');
            $table->string('year')->nullable();
            $table->text('grapes')->nullable();
            $table->text('vintage')->nullable();
            
            $table->boolean('confirmed')->default(false);
            $table->timestamps();
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::dropIfExists('wines');
	}

}
