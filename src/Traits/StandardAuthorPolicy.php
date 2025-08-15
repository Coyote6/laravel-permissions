<?php

namespace Coyote6\LaravelPermissions\Traits;


use App\Models\User;

use Coyote6\LaravelPermissions\Traits\AdminAccessTrait;
use Coyote6\LaravelPermissions\Traits\PolicyChecksOwner;
use Coyote6\LaravelPermissions\Traits\StandardPolicy;

use Illuminate\Database\Eloquent\Model;


trait StandardAuthorPolicy {
	
	use StandardPolicy,
		PolicyChecksOwner;

	
	// 
	// View
	//
	// @override
	//
	public function view (User $user, Model $model): bool {
		
		if (!$this->modelTypeIsValid ($model)) {
			return false;
		}
		
		if (
			$this->administer ($user) || 
			$user->hasPermissionTo ($this->getViewPrefix() . $this->getModelPermissionName()) ||
			$this->isOwner($user, $model)
		) {
			return true;
		}
		return false;
	}
	
	// 
	// Update
	//
	// @override
	//
	public function update (User $user, Model $model): bool {

		if (!$this->modelTypeIsValid ($model)) {
			return false;
		}
		
		if (
			$this->administer ($user) ||
			$user->hasPermissionTo ($this->getUpdatePrefix() . $this->getModelPermissionName()) ||
			$this->isOwner($user, $model)
		 ) {
			return true;
		}
		return false;
	}
	
	
	// 
	// Delete
	//
	// @override
	//
	public function delete (User $user, Model $model): bool {
		
		if (!$this->modelTypeIsValid ($model)) {
			return false;
		}
		
		if (
			$this->administer ($user) || 
			$user->hasPermissionToTo ($this->getDeletePrefix() . $this->getModelPermissionName()) ||
			$this->isOwner($user, $model)
		) {
			return true;
		}
		return false;
	}
	
	
	
}