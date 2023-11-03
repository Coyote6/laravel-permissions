<?php


namespace Coyote6\LaravelPermissions\Traits;


use App\Models\User;

use Illuminate\Database\Eloquent\Model;


trait PolicyChecksOwner {
	
	
	//
	// Get Owner Property
	//
	// @optionalParameter $modelOwnerProperty - string 
	// @default $modelOwnerProperty = 'author_id';
	// 
	// The get owner property method is used by the isOwner()
	// method to determine is the current user is the owner of
	// the model provided.
	//
	// @see $this->isOwner()
	//
	// @return string
	//
	protected function getOwnerProperty () {
		
		if (
			property_exists ($this, 'modelOwnerProperty') &&
			is_string ($this->modelOwnerProperty) &&
			$this->modelOwnerProperty != ''
		) {
			return $this->modelOwnerProperty;
		}
		
		return 'author_id';
		
	}
	
	
	//
	// Is Owner
	// 
	// Checks that the user is the owner of the model provided.
	//
	// @return bool
	//
	protected function isOwner (User $user, Model $model): bool {
		
		$ownerProperty = $this->getOwnerProperty();
	
		if ($model->$ownerProperty == $user->id) {
			return true;
		}
		return false;
		
	}
	
}