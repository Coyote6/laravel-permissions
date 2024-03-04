<?php

namespace Coyote6\LaravelPermissions\Models;


use App\Models\User;

use Coyote6\LaravelBase\Traits\GetAsOptions;
use Coyote6\LaravelBase\Traits\HasMachineNameAsId;
use Coyote6\LaravelBase\Traits\BootTraits;

use Coyote6\LaravelPermissions\Models\Permission;
use Coyote6\LaravelPermissions\Traits\HasPermissions;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Role extends Model {
	
	use HasFactory,
		GetAsOptions,
		HasPermissions,
		HasMachineNameAsId,
		BootTraits;
	
	public $incrementing = false;
	protected $keyType = 'string';
	public $timestamps = false;
	
	
	protected $fillable = [
	    'id',
	    'name'
	];
	
	public function __construct (array $attributes = []) {	
		parent::__construct($attributes);
		$this->table = config('permissions.tables.roles') ?: parent::getTable();
	}
    
    
    public function permissions () {
		return $this->belongsToMany (Permission::class, 'role_permissions');  
	}
	
    
    public function users () {
		return $this->belongsToMany (User::class, 'user_roles');
	}

	
}
