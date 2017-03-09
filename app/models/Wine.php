<?php

class Wine extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'wines';

	protected $fillable = ['vineyard_id', 'user_id', 'name', 'vintage', 'year', 'grapes', 'vintage', 'confirmed'];

	public function vineyard()
    {
        return $this->belongsTo('Vineyard');
    }

    public function users()
    {
        return $this->belongsToMany('User');
    }
}
