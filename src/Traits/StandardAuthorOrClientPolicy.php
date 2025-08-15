<?php

namespace Coyote6\LaravelPermissions\Traits;

use App\Models\User;

use Coyote6\LaravelPermissions\Traits\AdminAccessTrait;
use Coyote6\LaravelPermissions\Traits\PolicyChecksClientOwner;
use Coyote6\LaravelPermissions\Traits\PolicyChecksOwner;
use Coyote6\LaravelPermissions\Traits\StandardPolicy;

use Illuminate\Database\Eloquent\Model;


trait StandardAuthorOrClientPolicy {
	
	use StandardPolicy,
		PolicyChecksOwner,
		PolicyChecksClientOwner;
		
		
	public function administerClient (User $user): bool {
		if (
			$this->administer ($user) || 
			$user->hasPermissionTo ($this->getAdministerClientPrefix() . $this->getModelPermissionName())
		) {
			return true;
		}
		return false;
	}
		
	
	public function viewClientCrud (User $user): bool {
		if (
			$this->administer ($user) || 
			$user->hasPermissionTo ($this->getViewClientPrefix() . $this->getModelPermissionName())
		) {
			return true;
		}
		return false;
	}
	
	
	public function searchClient (User $user): bool {
		if (
			$this->administer ($user) || 
			$user->hasPermissionTo ($this->getSearchClientPrefix() . $this->getModelPermissionName())
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
			$user->hasPermissionTo ($this->getViewPrefix() . $this->getModelPermissionName()) ||
			$this->isOwner ($user, $model) ||
			(
				$this->isOwnedByClient ($user, $model) && 
				$user->hasPermissionTo ($this->getViewClientPrefix() . $this->getModelPermissionName())
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
			$user->hasPermissionTo ($this->getUpdatePrefix() . $this->getModelPermissionName()) ||
			$this->isOwner ($user, $model) ||
			(
				$this->isOwnedByClient ($user, $model) && 
				$user->hasPermissionTo ($this->getUpdateClientPrefix() . $this->getModelPermissionName())
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
			$user->hasPermissionTo ($this->getDeletePrefix() . $this->getModelPermissionName()) ||
			$this->isOwner ($user, $model) ||
			(
				$this->isOwnedByClient ($user, $model) && 
				$user->hasPermissionTo ($this->getDeleteClientPrefix() . $this->getModelPermissionName())
			)
		) {
			return true;
		}
		return false;
	}

	
}