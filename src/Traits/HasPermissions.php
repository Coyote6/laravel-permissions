<?php
	
	
namespace Coyote6\LaravelPermissions\Traits;


use Coyote6\LaravelPermissions\Models\Permission;


trait HasPermissions {
	
	
	public static function permissionKeyName () {
		static $key;
		if (is_null ($key)) {
			$key = Permission::make()->getKeyName();
		}
		return $key;
	}
	


	public function addPermissions (array $permissions = null, string $key = 'id') {
		
		if (is_null ($permissions)) {
			return $this;
		}
		
		$clean = [];
		$dirty = [];
		foreach ($permissions as $permission) {
			if ($permission instanceof Permission) {
				$clean[] = $permission;
			}
			else if (is_string ($permission) && $permission != '') {
				$dirty[] = $permission;
			}
		}

		if (count ($dirty) > 0) {
			$sanitized = Permission::whereIn ($key, $dirty)->get();
			foreach ($sanitized as $s) {
				$clean[] = $s;
			}
		}
		
		$this->permissions()->saveMany($clean);
		$this->refresh();
		return $this;
		
	}
	
	
	public function addPermission ($permission, string $key = 'id') {
		
		if ($permission instanceof Permission) {
		    $this->permissions()->save ($permission);
		}
		else if (is_string ($permission) && $permission != '') {
			$o = Permission::where($key, $permission)->first();
			if ($o instanceof Permission) {
				$this->permissions()->save ($o);
			}
		}
		
		$this->refresh();
		return $this;
		
	}
	
	
	public function removePermissions (array $permissions = null, string $key = 'id') {
		
		if (is_null ($permissions)) {
			return $this;
		}
		
		$clean = [];
		$dirty = [];
		foreach ($permissions as $permission) {
			if ($permission instanceof Permission) {
				$clean[] = $permission;
			}
			else if (is_string ($permission) && $permission != '') {
				if ($key == static::permissionKeyName()) {
					$clean[] = $permission;
				}
				else {
					$dirty[] = $permission;
				}
			}
		}

		if (count ($dirty) > 0) {
			$sanitized = Permission::whereIn ($key, $dirty)->get();
			foreach ($sanitized as $s) {
				$clean[] = $s;
			}
		}

		$this->permissions()->detach($clean);
		$this->refresh();
		return $this;
		
	}
	
	
	public function removePermission ($permission, string $key = 'id') {
		
		if ($permission instanceof Permission) {
		    $this->permissions()->detach ($permission);
		}
		else if (is_string ($permission) && $permission != '') {
			if ($key == static::permissionKeyName()) {
				$this->permissions()->detach ($permission);
			}
			else {
				$model = Permission::where($key, $permission)->first();
				if ($model instanceof Permission) {
					$this->permissions()->detach ($model);
				}
			}
		}
		
		$this->refresh();
		return $this;
		
	}
	
	
	public function updatePermissions (array $permissions = null, string $key = 'id') {
		
		if (is_null ($permissions)) {
			$permissions = [];
		}

		$clean = [];
		$dirty = [];
		foreach ($permissions as $permission) {
			if ($permission instanceof Permission) {
				$clean[] = $permission;
			}
			else if (is_string ($permission) && $permission != '') {
				if ($key == static::permissionKeyName()) {
					$clean[] = $permission;
				}
				else {
					$dirty[] = $permission;
				}
			}
		}
		
		if (count ($dirty) > 0) {
			$sanitized = Permission::whereIn ($key, $dirty)->get();
			foreach ($sanitized as $s) {
				$clean[] = $s;
			}
		}

		$this->permissions()->sync($clean);
		$this->refresh();
		return $this;
		
	}
	
	
	public function permissionIds () {
		return $this->permissions->mapWithKeys (function ($perm) {
			return [$perm->getKey() => $perm->getKey()];
		})->toArray();
	}
	
	
	public function permissionNames () {
		return $this->permissions->mapWithKeys (function ($perm) {
			return [$perm->getKey() => $perm->name];
		})->toArray();
	}

}