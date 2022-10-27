<?php


namespace Coyote6\LaravelPermissions\Policies;


use App\Models\User;
use Coyote6\LaravelPermissions\Models\Role;
use Coyote6\LaravelPermissions\Policies\BasePolicy;



class RolePolicy extends BasePolicy {
	
	
	public function viewCrud (User $user) {
		if ($user->hasPermission ('access roles admin page')) {
			return true;
		}
		return false;
	}
	
	
	
	public function view (User $user, Role $role) {
		if ($user->hasPermission ('view any role')) {
			return true;
		}
		return false;
	}
	
	
	public function create (User $user) {
		if ($user->hasPermission ('create role')) {
			return true;
		}
		return false;
	}
	
	
	public function update (User $user, Role $role) {
		if ($user->hasPermission ('edit any role')) {
			return true;
		}
		//    if ($user->hasPermission ('edit own role') && $user->role_id == $role->owner_id) {
		
		//   }
		return false;
	}
	
	
	public function delete (User $user, Role $role) {
		if ($user->hasPermission ('delete any role')) {
			return true;
		}
		return false;
	}


}

