<?php


namespace Coyote6\LaravelPermissions\Traits;


use App\Models\User;

use Illuminate\Database\Eloquent\Model;


trait PolicyChecksClientOwner {
	
	
	//
	// Get Client Property
	//
	// @optionalParameter $modelClientProperty - string 
	// @default $modelClientProperty = 'client_id';
	// 
	// The get client property method is used by the isOwnedByClient()
	// method to determine is the current user's client is the owner of
	// the model provided.
	//
	// @see $this->isOwnedByClient()
	//
	// @return string
	//
	protected function getClientProperty () {
		
		if (
			property_exists ($this, 'modelClientProperty') &&
			is_string ($this->modelClientProperty) &&
			$this->modelClientProperty != ''
		) {
			return $this->modelClientProperty;
		}
		
		return 'client_id';
		
	}
	
	
	//
	// Is Owned By Client
	//
	// Checks if the user's client is the owner of the model provided.
	//
	// @return bool
	//
	protected function isOwnedByClient (User $user, Model $model): bool {
	
		$clientProperty = $this->getClientProperty();
	
		if ($model->$clientProperty == $user->$clientProperty) {
			return true;
		}
		return false;
	}
	
}

