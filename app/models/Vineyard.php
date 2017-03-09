<?php

class Vineyard extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'vineyards';

	protected $fillable = ['user_id', 'name', 'notes', 'grapes', 'city', 'region', 'country', 'lat', 'lng', 'confirmed'];

}
