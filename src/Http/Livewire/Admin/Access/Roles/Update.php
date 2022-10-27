<?php


namespace Coyote6\LaravelPermissions\Http\Livewire\Admin\Access\Roles;


use Coyote6\LaravelPermissions\Models\Role;
use Coyote6\LaravelPermissions\Models\Permission;
use Coyote6\LaravelCrud\Crud\Update as CrudUpdate;


class Update extends CrudUpdate {
	
	//
	// Crud Properties
	//
	
	public Role $model;
	public $crudRoute = 'admin.access.roles';
	public $route = 'admin.access.roles.update';
	public $routeKey = 'role';
	public $title = 'Update Role';
	public $successMessage = 'The role was successfully updated.';
	
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

	public function mount (Role $role = null) {
		if (is_null ($role)) {
			$role = Role::make();
			$this->customProperties();
		}
		$this->model = $role;

	}
	
	
	public function updateFallback (Role $role) {
		$this->model = $role;
		return $this->processUpdateFallback();
	}
	
	
	public function show (Role $role) {
		if ($this->model->isNot($role)) {
			$this->model = $role;
			$this->customProperties();
			$this->resetErrorBag();
		}		
		$this->emitUp ('toggleEditModal');
	}
	
	protected function customProperties () {
		$permissions = $this->model->permissions;
		$this->permissions = $permissions->mapWithKeys(function ($item, $key) {
			return [$item->id => $item->id];
		})->toArray();
		$this->permissionsSelected = $this->model->permissionNames();
	}
	
	
	public function resetForm (Role $role) {
		$this->model = $role;
		$this->customProperties();
		$this->resetErrorBag();
	}
	
	
	
	public function postSave (&$vals) {
		$this->model->updatePermissions ($this->permissions, 'id');
	}
		
	public function postSaveFallback (&$vals) {
		$this->model->updatePermissions ($this->permissions, 'id');
	}
	
	
	//
	// AutoFill Methods
	//
	
	public function updatedPermissionsSearch ($value) {
		$this->permissionsField()->search ($value);
	}
	
	
	public function autofillSelectedPermissions ($id, $name) {
		$this->permissionsField()->select ($id, $name);
	}
	
	
	public function autofillRemovePermissions ($id) {
		$this->permissionsField()->remove ($id);
	}
	
	
	
	//
	// Form Methods
	//
	
	protected function formFields (&$form) {
		
		 $form->text('id')
			->lwDebounce('model.id')
			->label ('Id')
			->value ($this->model->id)
			->disabled();
			
	    
		$form->text('name')
			->lwDebounce('model.name')
			->label ('Name')
			->placeholder('Name')
			->required()
			->value ($this->model->name)
			->addRules(['min:3','max:191', 'unique:roles,name,' . $this->model->getKey()]);


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
