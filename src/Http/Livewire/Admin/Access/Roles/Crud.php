<?php


namespace Coyote6\LaravelPermissions\Http\Livewire\Admin\Access\Roles;


use Coyote6\LaravelPermissions\Models\Role;
use Coyote6\LaravelCrud\Crud\Crud as BaseCrud;


class Crud extends BaseCrud {	
	
	
	public $routeName = 'admin.access.roles';

	public $importForm = 'laravel-permissions::admin.access.roles.import';
	public $createForm = 'laravel-permissions::admin.access.roles.create';
	public $updateForm = 'laravel-permissions::admin.access.roles.update';
	public $deleteForm = 'laravel-permissions::admin.access.roles.delete';
	
	public $createRoute = 'admin.access.roles.create';
	public $updateRoute = 'admin.access.roles.edit';
	public $deleteRoute = 'admin.access.roles.delete';
		
	public $bulkExportSuccessMessage = 'The selected roles were successfully deleted.';

	public $filters = [
		'search' => '',
		'id' => '',
		'name' => ''
	];

	
	public $columns = [
		'name' => 'Name',
	]; 
    
    
    
    public function advancedSearchFormFields (&$form) {
		$form->text ('search-id')
			->lwDebounce ('filters.id')
			->placeholder ('Id');
		$form->text ('name')
			->lwDebounce ('filters.name')
			->placeholder ('Name');
	}
	
	
	public function getQueryProperty() {
		
		$query = Role::query()
			->when($this->filters['search'], fn($query, $search) => $query->multiFieldSearch('id,name', $search))
			->when($this->filters['id'], fn($query, $search) => $query->search('id', $search))
			->when($this->filters['name'], fn($query, $search) => $query->search('name', $search));

		return $this->applySorting ($query);
		
	}
	

}
