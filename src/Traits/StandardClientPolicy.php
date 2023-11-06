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
		
	
	public function administerClient (User $user): bool {
		if (
			$this->administer ($user) || 
			$user->hasPermission ($this->getAdministerClientPrefix() . $this->getModelPermissionName())
		) {
			return true;
		}
		return false;
	}
	
		
	public function viewClientCrud (User $user): bool {
		if (
			$this->administer ($user) || 
			$user->hasPermission ($this->getViewClientPrefix() . $this->getModelPermissionName())
		) {
			return true;
		}
		return false;
	}
	
	
	public function searchClient (User $user): bool {
		if (
			$this->administer ($user) || 
			$user->hasPermission ($this->getSearchClientPrefix() . $this->getModelPermissionName())
		) {
			return true;
		}
		return false;
	}
	
	
	public function view (User $user, Model $model): bool {
		
		if (!$this->modelTypeIsValid ($model)) {
			return false;
		}
		
		if (
			$this->administer ($user) || 
			$user->hasPermission ($this->getViewPrefix() . $this->getModelPermissionName()) ||
			(
				$this->isOwnedByClient ($user, $model) && 
				$user->hasPermission ($this->getViewClientPrefix() . $this->getModelPermissionName())
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
			$user->hasPermission ($this->getUpdatePrefix() . $this->getModelPermissionName()) ||
			(
				$this->isOwnedByClient ($user, $model) && 
				$user->hasPermission ($this->getUpdateClientPrefix() . $this->getModelPermissionName())
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
			$user->hasPermission ($this->getDeletePrefix() . $this->getModelPermissionName()) ||
			(
				$this->isOwnedByClient ($user, $model) && 
				$user->hasPermission ($this->getDeleteClientPrefix() . $this->getModelPermissionName())
			)
		) {
			return true;
		}
		return false;
	}

	
}