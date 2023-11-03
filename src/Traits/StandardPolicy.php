<?php

namespace Coyote6\LaravelPermissions\Traits;


use App\Models\User;

use Coyote6\LaravelPermissions\Traits\AdminAccessTrait;
use Coyote6\LaravelPermissions\Traits\PolicyValidatesModel;

use Illuminate\Database\Eloquent\Model;


trait StandardPolicy {
	
	use AdminAccessTrait,
		PolicyValidatesModel;
	
	
	public function administer (User $user): bool {
		if ($user->hasPermission ('administer_' . $this->getModelPermissionName())) {
			return true;
		}
		return false;
	}
	
	
	public function viewCrud (User $user): bool {
		if ($this->administer ($user) || $user->hasPermission ('administer_' . $this->getModelPermissionName())) {
			return true;
		}
		return false;
	}
	
	
	public function viewUserCrud (User $user): bool {
		if ($this->administer ($user) || $user->hasPermission ('view_' . $this->getModelPermissionName())) {
			return true;
		}
		return false;
	}
	
	
	public function search (User $user): bool {
		if ($this->administer ($user) || $user->hasPermission ('search_' . $this->getModelPermissionName())) {
			return true;
		}
		return false;
	}
	
	
	public function view (User $user, Model $model): bool {
		
		// Security check to make sure the supplied model is
		// the model type we are validating.
		//
		if (!$this->modelTypeIsValid ($model)) {
			return false;
		}
		
		if ($this->administer ($user) || $user->hasPermission ('view_' . $this->getModelPermissionName())) {
			return true;
		}
		return false;
	}
	
	
	public function create (User $user): bool {
		if ($this->administer ($user) || $user->hasPermission ('create_' . $this->getModelPermissionName())) {
			return true;
		}
		return false;
	}
	
	
	public function update (User $user, Model $model): bool {
		
		// Security check to make sure the supplied model is
		// the model type we are validating.
		//
		if (!$this->modelTypeIsValid ($model)) {
			return false;
		}
		
		if ($this->administer ($user) || $user->hasPermission ('edit_' . $this->getModelPermissionName())) {
			return true;
		}
		return false;
	}
	
	
	public function delete (User $user, Model $model): bool {
		
		// Security check to make sure the supplied model is
		// the model type we are validating.
		//
		if (!$this->modelTypeIsValid ($model)) {
			return false;
		}
		
		if ($this->administer ($user) || $user->hasPermission ('delete_' . $this->getModelPermissionName())) {
			return true;
		}
		return false;
	}
	
}