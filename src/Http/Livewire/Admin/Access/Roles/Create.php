<?php


namespace Coyote6\LaravelPermissions\Http\Livewire\Admin\Access\Roles;


use Coyote6\LaravelPermissions\Models\Role;
use Coyote6\LaravelPermissions\Models\Permission;

use Coyote6\LaravelCrud\Crud\Create as CrudCreate;
use Coyote6\LaravelCrud\Traits\DefaultCreateMount;


class Create extends CrudCreate {
	

	use DefaultCreateMount;

	//
	// Crud Properties
	//
		
	public Role $model;
	protected $crudRoute = 'admin.access.roles';
	protected $route = 'admin.access.roles.create';
	public $title = 'Create Role';
	public $successMessage = 'The role was successfully created.';
	
	public $permissions = [];
	
	//
	// Autofill Properties
	//

	protected $messages = [
        'model.pids.exists' => 'The selected permissions are invalid.',
    ];
    
	
	//
	// CRUD Methods
	//

	public function modelClass () {
		return Role::class;
	}


	public function postSave (&$vals) {
		$this->model->updatePermissions ($this->permissions, 'id');
	}

		
	public function postSaveFallback (&$vals) {
		$this->model->updatePermissions($this->permissions, 'id');
	}

	
	//
	// Autofill Methods
	//
	
	
	public function updatedPermissionsSearch ($value) {
		$this->permissionsField()->search ($value);
	}
	
	
	public function autofillSelectedPermissions ($id, $name) {
		$this->permissionsField()->select ($id, $name);
	}
	
	
	public function autofillRemovePermissions ($id) {
		$this->permissionsField()->remove($id);
	}
	
	
	
	//
	// Form Methods
	//
	
	protected function formFields (&$form) {
	    
		$form->text('name')
			->lwDebounce('model.name')
			->required()
			->label ('Name')
			->addAttribute('placeholder', 'Name')
			->addRules(['min:3', 'max:191', 'unique:roles,name']);
			
		$form->autofill ('permissions')
			->lwDebounce ('permissions')
			->table ('permissions')
			->searchClass (Permission::class)
			->label ('Permissions');
			
	}
	
	protected function permissionsField () {
		return $this->form()->find('permissions');
	}
    

}
