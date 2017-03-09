<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVineyardTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('vineyards', function($table) {
            $table->increments('id');

            $table->integer('user_id')->unsigned()->index()->nullable();
			$table->foreign('user_id')->references('id')->on('users')->onDelete(DB::raw('set null'));

            $table->string('name');
            $table->text('notes')->nullable();
            $table->text('grapes')->nullable();

            $table->string('city')->nullable();
            $table->string('region')->nullable();
            $table->string('country')->nullable();

            $table->double('lat', 12, 8);
			$table->double('lng', 12, 8);

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
        Schema::dropIfExists('vineyards');
	}

}
