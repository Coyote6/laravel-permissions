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
	
	
	//
	// Administer Client Prefix
	//
	// @optionalProp $administerClientPrefix - string
	// @default $administerClientPrefix = 'administer_client_';
	//
	// Allows you to override the administer client prefix.
	//
	// @return string
	//
	public function getAdministerClientPrefix (): string {
		if (
			property_exists ($this, 'administerClientPrefix') &&
			is_string ($this->administerClientPrefix)
		) {
			return $this->administerClientPrefix;
		}
		return 'administer_client_';
	}
	
	
	//
	// View Client Prefix
	//
	// @optionalProp $viewClientPrefix - string
	// @default $viewClientPrefix = 'view_client_';
	//
	// Allows you to override the view client prefix.
	//
	// @return string
	//
	public function getViewClientPrefix (): string {
		if (
			property_exists ($this, 'viewClientPrefix') &&
			is_string ($this->viewClientPrefix)
		) {
			return $this->viewClientPrefix;
		}
		return 'view_client_';
	}
	
	
	//
	// Search Client Prefix
	//
	// @optionalProp $searchClientPrefix - string
	// @default $searchClientPrefix = 'search_client_';
	//
	// Allows you to override the search client prefix.
	//
	// @return string
	//
	public function getSearchClientPrefix (): string {
		if (
			property_exists ($this, 'searchClientPrefix') &&
			is_string ($this->searchClientPrefix)
		) {
			return $this->searchClientPrefix;
		}
		return 'search_client_';
	}
	
	
	
	//
	// Update Client Prefix
	//
	// @optionalProp $updateClientPrefix - string
	// @default $updateClientPrefix = 'update_client';
	//
	// Allows you to override the update client prefix.
	//
	// @return string
	//
	public function getUpdateClientPrefix (): string {
		if (
			property_exists ($this, 'updateClientPrefix') &&
			is_string ($this->updateClientPrefix)
		) {
			return $this->updateClientPrefix;
		}
		return 'update_client_';
	}
	
	
	//
	// Delete Prefix
	//
	// @optionalProp $deletePrefix - string
	// @default $deletePrefix = 'delete_';
	//
	// Allows you to override the delete client prefix.
	//
	// @return string
	//
	public function getDeleteClientPrefix (): string {
		if (
			property_exists ($this, 'deleteClientPrefix') &&
			is_string ($this->deleteClientPrefix)
		) {
			return $this->deleteClientPrefix;
		}
		return 'delete_client_';
	}
	
}

