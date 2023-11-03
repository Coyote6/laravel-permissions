<?php

namespace Coyote6\LaravelPermissions\Traits;


use App\Models\User;

use Coyote6\LaravelPermissions\Traits\AdminAccessTrait;
use Coyote6\LaravelPermissions\Traits\PolicyChecksClientOwner;
use Coyote6\LaravelPermissions\Traits\StandardPolicy;

use Illuminate\Database\Eloquent\Model;


trait StandardClientPolicy {
	
	use StandardPolicy,
		PolicyChecksClientOwner;
	
	
	public function view (User $user, Model $model): bool {
		
		if (!$this->modelTypeIsValid ($model)) {
			return false;
		}
		
		if (
			$this->administer ($user) || 
			$user->hasPermission ('view_' . $this->getModelPermissionName()) ||
			(
				$this->isOwnedByClient ($user, $model) && $user->hasPermission ('view_clients_' . $this->getModelPermissionName())
			)
		) {
			return true;
		}
		return false;
	}
	
	
	public function update (User $user, Model $model): bool {
		
		if (!$this->modelTypeIsValid ($model)) {
			return false;
		}
		
		if (
			$this->administer ($user) ||
			$user->hasPermission ('edit_' . $this->getModelPermissionName()) ||
			(
				$this->isOwnedByClient ($user, $model) && $user->hasPermission ('edit_clients_' . $this->getModelPermissionName())
			)
		 ) {
			return true;
		}
		return false;
	}
	
	
	public function delete (User $user, Model $model): bool {
		
		if (!$this->modelTypeIsValid ($model)) {
			return false;
		}
		
		if (
			$this->administer ($user) || 
			$user->hasPermission ('delete_' . $this->getModelPermissionName()) ||
			(
				$this->isOwnedByClient ($user, $model) && $user->hasPermission ('delete_clients_' . $this->getModelPermissionName())
			)
		) {
			return true;
		}
		return false;
	}

	
}