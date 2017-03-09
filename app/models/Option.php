<?php

class Option extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'options';

	protected $fillable = ['user_id', 'key', 'value'];

}
