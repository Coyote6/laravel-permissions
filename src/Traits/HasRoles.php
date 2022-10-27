<?php
	
	
namespace Coyote6\LaravelPermissions\Traits;


use Coyote6\LaravelPermissions\Models\Permission;
use Coyote6\LaravelPermissions\Models\Role;


trait HasRoles {
	
	
	public static function roleKeyName () {
		static $key;
		if (is_null ($key)) {
			$key = Role::make()->getKeyName();
		}
		return $key;
	}
	

	public function hasRole ($role, string $key = 'id') : bool {
		
		$role = trim ($role);
		
		if (is_string ($role) && $role != '') {
			$roleObj = Role::find($role);
			if (is_object ($roleObj)) {
				$role = $roleObj;
			}
			else {
				$role = Role::where($key, $role)->first();
			}
		}
		
		if (!is_object ($role) || !$role instanceof Role) {
			return false;
		}

		if ($this->roles->contains ($role)) {
			return true;
		}
		return false;
		
	}


	public function addRoles (array $roles, string $key = 'id') {
		
		if (is_null ($roles)) {
			return $this;
		}
		
		$clean = [];
		$dirty = [];
		foreach ($roles as $role) {
			if ($role instanceof Role) {
				$clean[] = $role;
			}
			else if (is_string ($role) && $role != '') {
				$dirty[] = $role;
			}
		}

		if (count ($dirty) > 0) {
			$sanitized = Role::whereIn ($key, $dirty)->get();
			foreach ($sanitized as $s) {
				$clean[] = $s;
			}
		}
		
		$this->roles()->saveMany($clean);
		$this->refresh();
		return $this;
		
	}
	
	
	public function addRole ($role, string $key = 'id') {
		
		if ($role instanceof Role) {
		    $this->roles()->save ($role);
		}
		else if (is_string ($role) && $role != '') {
			$r = Role::where($key, $role)->first();
			if ($r instanceof Role) {
				$this->roles()->save ($r);
			}
		}
		
		$this->refresh();
		return $this;
		
	}
	
	
	public function removeRoles (array $roles, string $key = 'id') {
		
		if (is_null ($roles)) {
			return $this;
		}
		
		$clean = [];
		$dirty = [];
		foreach ($roles as $role) {
			if ($role instanceof Role) {
				$clean[] = $role;
			}
			else if (is_string ($role) && $role != '') {
				if ($key == static::roleKeyName()) {
					$clean[] = $role;
				}
				else {
					$dirty[] = $role;
				}
			}
		}

		if (count ($dirty) > 0) {
			$sanitized = Role::whereIn ($key, $dirty)->get();
			foreach ($sanitized as $s) {
				$clean[] = $s;
			}
		}

		$this->roles()->detach($clean);
		$this->refresh();
		return $this;
		
	}
	
	
	public function removeRole ($role, string $key = 'id') {
		

		if ($role instanceof Role) {
		    $this->roles()->detach ($role);
		}
		else if (is_string ($role) && $role != '') {
			if ($key == static::roleKeyName()) {
				$this->roles()->detach ($role);
			}
			else {
				$r = Role::where($key, $role)->first();
				if ($r instanceof Role) {
					$this->roles()->detach ($r);
				}
			}
		}
		
		$this->refresh();
		return $this;
		
	}
	
	
	public function updateRoles (array $roles, string $key = 'id') {
		
		if (is_null ($roles)) {
			$roles = [];
		}
		
		$clean = [];
		$dirty = [];
		foreach ($roles as $role) {
			if ($role instanceof Role) {
				$clean[] = $role;
			}
			else if (is_string ($role) && $role != '') {
				if ($key == static::roleKeyName()) {
					$clean[] = $role;
				}
				else {
					$dirty[] = $role;
				}
			}
		}
		
		if (count ($dirty) > 0) {
			$sanitized = Role::whereIn ($key, $dirty)->get();
			foreach ($sanitized as $s) {
				$clean[] = $s;
			}
		}
		
		$this->roles()->sync($clean);
		$this->refresh();
		return $this;
		
	}
	
	
	public function roleIds () {
		return $this->roles->mapWithKeys (function ($role) {
			return [$role->getKey() => $role->getKey()];
		})->toArray();
	}
	
	
	public function roleNames () {
		return $this->roles->mapWithKeys (function ($role) {
			return [$role->getKey() => $role->name];
		})->toArray();
	}
	
	
	//
	// Permissions
	//
	
	public function permissions (bool $refresh = null) {
		static $permissions;
		if (is_null ($permissions) || $refresh === true) {
			if ($this->hasRole('administrator')) {
				$permissions = Permissions::all();
			}
			else {
				$permissionCollection = [];
				foreach ($this->roles as $role) {
					foreach ($role->permissions as $permission) {
						if (!isset ($permissionCollection[$permission->getKey()])) {
							$permissionCollection[$permission->getKey()] = $permission->name;
						}
					}
				}
				if (is_callable ([$this, 'memberships'])) {
					foreach ($this->memberships as $membership) {
						foreach ($membership->roles as $role) {
							foreach ($role->permissions as $permission) {
								if (!isset ($permissionCollection[$permission->getKey()])) {
									$permissionCollection[$permission->getKey()] = $permission->name;
								}
							}
						}
					}
				}
				$permissions = collect ($permissionCollection);
			}
		}
		return $permissions;
	}


	public function hasPermission ($permissions, string $key = 'id') {
		
		if ($this->hasRole('administrator')) {
			return true;
		}
		
		$ps = [];  
		if (is_array ($permissions)) {
			foreach ($permissions as $p) {
				if ($p instanceof Permission) {
					$ps[] = $p;
				}
				else {
					$permission = Permission::where($key, $p)->first();
					if ($permission) {
						$ps[] = $permission;
					}
				}
			}
		}
		else if (is_string ($permissions) && $permissions != '') {
			foreach (explode('|', $permissions) as $permission) {
				$permission = Permission::where ($key, $permission)->first();
				if ($permission) {
					$ps[] = $permission;
				}
			}
		}
		else if ($permissions instanceof Permission) {
			$ps[] = $permissions;
		}
		else {
			return false;
		}

		// Loop through the permissions array and see
		// if any of the roles that the permissions are
		// attached to, have the same role as this user
		// or object.
		//
		foreach ($ps as $permission) {
			foreach ($permission->roles as $role){
				if ($this->roles->contains ($role)) {
					return true;
				}
			}
		}
		return false;
		
	}
	
	
	public function hasPermissionTo ($permission) {
		return $this->hasPermission ($permission);
	}
	

}