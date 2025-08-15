<?php

namespace Coyote6\LaravelPermissions\Traits;


use App\Models\User;

use Coyote6\LaravelPermissions\Traits\AdminAccessTrait;
use Coyote6\LaravelPermissions\Traits\PolicyValidatesModel;

use Illuminate\Database\Eloquent\Model;


trait StandardPolicy {
	
	use AdminAccessTrait,
		PolicyValidatesModel;
		
	//
	// Model Permission Name
	//
	// @prop $modelPermissionName - string
	//
	// The $modelPermissionName property is used to find the permissions needed to
	// determine if the user has permission.  It is the main word(s) in the permission
	// name, without the action. For example:
	//
	// In the permission following permissions 'create models', 'edit models', and
	// 'delete models' the $modelPermissionName would be 'models' and the action words
	// would be 'create', 'edit', 'delete'.
	//
	// A standard policy has the following actions:
	// administer
	// create
	// edit
	// delete
	// view
	// search
	//
	// @return string
	//
	protected function getModelPermissionName (): string {
		
		if (
			property_exists ($this, 'modelPermissionName') &&
			is_string ($this->modelPermissionName) &&
			$this->modelPermissionName != ''
		) {
			return $this->modelPermissionName;
		}
		else {
			trigger_error ('Please be sure to set the $modelPermissionName property on the ' . __CLASS__ . ' policy.');
		}
			
	}
	
	//
	// Permission Prefixes
	//
	
	
	//
	// Administer Prefix
	//
	// @optionalProp $administerPrefix - string
	// @default $administerPrefix = 'administer_';
	//
	// Allows you to override the administer prefix.
	//
	// @return string
	//
	protected function getAdministerPrefix (): string {
		if (
			property_exists ($this, 'administerPrefix') &&
			is_string ($this->administerPrefix)
		) {
			return $this->administerPrefix;
		}
		return 'administer_';
	}
	
	
	//
	// View Prefix
	//
	// @optionalProp $viewPrefix - string
	// @default $viewPrefix = 'view_';
	//
	// Allows you to override the view prefix.
	//
	// @return string
	//
	protected function getViewPrefix (): string {
		if (
			property_exists ($this, 'viewPrefix') &&
			is_string ($this->viewPrefix)
		) {
			return $this->viewPrefix;
		}
		return 'view_';
	}
	
	
	//
	// Search Prefix
	//
	// @optionalProp $searchPrefix - string
	// @default $searchPrefix = 'search_';
	//
	// Allows you to override the search prefix.
	//
	// @return string
	//
	protected function getSearchPrefix (): string {
		if (
			property_exists ($this, 'searchPrefix') &&
			is_string ($this->searchPrefix)
		) {
			return $this->searchPrefix;
		}
		return 'search_';
	}
	
	
	//
	// Create Prefix
	//
	// @optionalProp $createPrefix - string
	// @default $createPrefix = 'create_';
	//
	// Allows you to override the create prefix.
	//
	// @return string
	//
	protected function getCreatePrefix (): string {
		if (
			property_exists ($this, 'createPrefix') &&
			is_string ($this->createPrefix)
		) {
			return $this->createPrefix;
		}
		return 'create_';
	}
	
	
	//
	// Update Prefix
	//
	// @optionalProp $updatePrefix - string
	// @default $updatePrefix = 'update_';
	//
	// Allows you to override the update prefix.
	//
	// @return string
	//
	protected function getUpdatePrefix (): string {
		if (
			property_exists ($this, 'updatePrefix') &&
			is_string ($this->updatePrefix)
		) {
			return $this->updatePrefix;
		}
		return 'update_';
	}
	
	
	//
	// Delete Prefix
	//
	// @optionalProp $deletePrefix - string
	// @default $deletePrefix = 'delete_';
	//
	// Allows you to override the delete prefix.
	//
	// @return string
	//
	protected function getDeletePrefix (): string {
		if (
			property_exists ($this, 'deletePrefix') &&
			is_string ($this->deletePrefix)
		) {
			return $this->deletePrefix;
		}
		return 'delete_';
	}
	
	
	//
	// Policy Actions
	//
	
	public function administer (User $user): bool {
		if ($user->hasPermissionTo ($this->getAdministerPrefix() . $this->getModelPermissionName())) {
			return true;
		}
		return false;
	}
	
	
	public function viewCrud (User $user): bool {
		if ($this->administer ($user) || $user->hasPermissionTo ($this->getAdministerPrefix() . $this->getModelPermissionName())) {
			return true;
		}
		return false;
	}
	
	
	public function viewUserCrud (User $user): bool {
		if ($this->administer ($user) || $user->hasPermissionTo ($this->getViewPrefix() . $this->getModelPermissionName())) {
			return true;
		}
		return false;
	}
	
	
	public function search (User $user): bool {
		if ($this->administer ($user) || $user->hasPermissionTo ($this->getSearchPrefix() . $this->getModelPermissionName())) {
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
		
		if ($this->administer ($user) || $user->hasPermissionTo ($this->getViewPrefix() . $this->getModelPermissionName())) {
			return true;
		}
		return false;
	}
	
	
	public function create (User $user): bool {
		if ($this->administer ($user) || $user->hasPermissionTo ($this->getCreatePrefix() . $this->getModelPermissionName())) {
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
		
		if ($this->administer ($user) || $user->hasPermissionTo ($this->getUpdatePrefix() . $this->getModelPermissionName())) {
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
		
		if ($this->administer ($user) || $user->hasPermissionTo ($this->getDeletePrefix() . $this->getModelPermissionName())) {
			return true;
		}
		return false;
	}
	
}