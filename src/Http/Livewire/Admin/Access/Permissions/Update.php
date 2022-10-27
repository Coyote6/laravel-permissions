<?php


namespace Coyote6\LaravelPermissions\Http\Livewire\Admin\Access\Permissions;


use Coyote6\LaravelPermissions\Models\Permission;
use Coyote6\LaravelCrud\Crud\Update as CrudUpdate;


class Update extends CrudUpdate {
	
		
	public Permission $model;
	public $crudRoute = 'admin.access.permissions';
	public $route = 'admin.access.permissions.update';
	public $routeKey = 'permission';
	public $title = 'Update Permission';
	public $successMessage = 'The permission was successfully updated.';
	

	public function mount (Permission $permission = null) {
		if (is_null ($permission)) {
			$permission = Permission::make();
		}
		$this->model = $permission;
	}
	
	
	public function updateFallback (Permission $permission) {
		$this->model = $permission;
		return $this->processUpdateFallback();
	}
	
	
	public function show (Permission $permission) {
		if ($this->model->isNot($permission)) {
			$this->model = $permission;
			$this->resetErrorBag();
		}		
		$this->emitUp ('toggleEditModal');
	}
	
	
	public function resetForm (Permission $permission) {
		$this->model = $permission;
		$this->resetErrorBag();
	}

	
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
			->addRules(['min:3','max:191', 'unique:permissions,name,' . $this->model->getKey()]);
		    
	}
    

}
