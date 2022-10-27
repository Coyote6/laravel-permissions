<?php


namespace Coyote6\LaravelPermissions\Http\Livewire\Admin\Access\Roles;


use Coyote6\LaravelPermissions\Models\Role;
use Coyote6\LaravelCrud\Crud\Delete as CrudDelete;


class Delete extends CrudDelete {
	
		
	public Role $model;
	public $title = 'Delete Role';

	protected $crudRoute = 'admin.access.roles';
	protected $route = 'admin.access.roles.delete';
	public $routeKey = 'role';
	public $successMessage = 'The role was successfully deleted.';
	public $confirmationMessage = 'Are you sure you wish to delete role: <strong>%s</strong>?';
	
	

	public function mount (Role $role = null) {
		if (is_null ($role)) {
			$model = Role::make();
		}
		$this->model = $role;
	}
	
	
	public function show (Role $role) {
		if ($this->model->isNot($role)) {
			$this->model = $role;
		}		
		$this->emitUp ('toggleDeleteModal');
	}
	
	
	public function destroyFallback (Role $role) {
		$this->model = $role;
		return $this->processDestroyFallback();
	}
	
    
    protected function confirmationMessage () {
		return sprintf ($this->confirmationMessage, $this->model->name);
	}
 

}
