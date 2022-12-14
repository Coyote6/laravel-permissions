<?php



namespace Coyote6\LaravelPermissions\Policies;



use App\Models\User;
use Coyote6\LaravelPermissions\Models\Permission;
use Coyote6\LaravelPermissions\Policies\BasePolicy;



class PermissionPolicy extends BasePolicy {
	
	
	public function viewCrud (User $user) {
		if ($user->hasPermission ('access permissions admin page')) {
			return true;
		}
		return false;
	}
	
	
	
	public function view (User $user, Permission $permission) {
		if ($user->hasPermission ('view any permission')) {
			return true;
		}
		return false;
	}
	
	
	public function create (User $user) {
		if ($user->hasPermission ('create permission')) {
			return true;
		}
		return false;
	}
	
	
	public function update (User $user, Permission $permission) {
		if ($user->hasPermission ('edit any permission')) {
			return true;
		}
		//    if ($user->hasPermission ('edit own permission') && $user->permission_id == $permission->owner_id) {
		
		//   }
		return false;
	}
	
	
	public function delete (User $user, Permission $permission) {
		if ($user->hasPermission ('delete any permission')) {
			return true;
		}
		return false;
	}


}

