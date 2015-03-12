<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use SammyK\LaravelFacebookSdk\FacebookableTrait;

class User extends Eloquent implements UserInterface {

	use UserTrait;
	use FacebookableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	protected $hidden = ['access_token'];

	public $timestamps = false;

	public function news() {
		return $this->hasMany('News', 'user_id', 'id');
	}

	/**
	 * @param $user Facebook User object to create or update in the database.
     */
	public static function createOrUpdate($fbUser) {

		$dbUser = User::find($fbUser['id']);

		if (is_null($dbUser)) {
			// Not in DB yet, create it
			$user = new User;
			$user->id = $fbUser['id'];
			$user->name = $fbUser['name'];
			$user->real_name = $user->name;
			$user->save();

			return $user;
		} else {
			// User already in DB, assign news name if necessary
			$dbUser->name = $fbUser['name'];
			$dbUser->save();

			return $dbUser;
		}
	}
}
