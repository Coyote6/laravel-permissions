<?php


namespace Coyote6\LaravelPermissions\Providers;


use Coyote6\LaravelBase\Traits\ServiceProviderSeedsDb;

#use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
#use Illuminate\View\Compilers\BladeCompiler;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Gate;

class PermissionsServiceProvider extends ServiceProvider {
	
	use ServiceProviderSeedsDb;
	

	protected $policies = [
    	'Coyote6\LaravelPermissions\Models\Permission' => 'Coyote6\LaravelPermissions\Policies\PermissionPolicy',
    	'Coyote6\LaravelPermissions\Models\Role' => 'Coyote6\LaravelPermissions\Policies\RolePolicy',
    ];
    
    
    protected $livewireComponents = [
	  'admin.access.permissions.crud' => 'Admin\Access\Permissions\Crud',
	  'admin.access.permissions.create' => 'Admin\Access\Permissions\Create',
	  'admin.access.permissions.update' => 'Admin\Access\Permissions\Update', 
	  'admin.access.permissions.delete' => 'Admin\Access\Permissions\Delete', 
	  'admin.access.permissions.import' => 'Admin\Access\Permissions\Import', 
	  'admin.access.roles.crud' => 'Admin\Access\Roles\Crud',
	  'admin.access.roles.create' => 'Admin\Access\Roles\Create',
	  'admin.access.roles.update' => 'Admin\Access\Roles\Update', 
	  'admin.access.roles.delete' => 'Admin\Access\Roles\Delete', 
	  'admin.access.roles.import' => 'Admin\Access\Roles\Import', 
    ];
    
    
	/**
	 * Register services.
	 *
	 * @return void
	 */
	public function register() {
		$this->mergeConfigFrom (__DIR__ . '/../Resources/config/permissions.php', 'permissions');
#		$this->loadViewsFrom (__DIR__ . '/../Resources/views', 'laravel-crud');
#		$this->loadPoliciesFrom (__DIR__ . '/../Policies');
	}


	/**
	 * Bootstrap services.
	 *
	 * @return void
	 */
	public function boot() {
		
		$this->publishes([
			__DIR__ . '/../Resources/config/permissions.php' => config_path('permissions.php'),
		], 'permissions');
		
		$this->registerPolicies();
		$this->registerLivewireComponents();
		$this->registerBladeDirectives();
		
		$this->loadMigrationsFrom (__DIR__.'/../Resources/database/migrations');
		$this->seedDbOnCommand (__DIR__ . '/../Resources/database/seeders');
		
		if (class_exists ('Coyote6\LaravelCrud\Providers\CrudServiceProvider')) {
			Route::middleware('web')
	            ->group (__DIR__ . '/../Resources/routes/admin.php');
		}
		
	}
	
	
	
	protected function registerPolicies() {
        foreach ($this->policies as $key => $value) {
            Gate::policy($key, $value);
        }
    }
    
    
    protected function registerLivewireComponents () {
		if (class_exists ('Livewire\Livewire')) {
        	foreach ($this->livewireComponents as $name => $class) {
				\Livewire\Livewire::component('laravel-permissions::' . $name, 'Coyote6\LaravelPermissions\Http\Livewire\\' . $class);
			}
		}
    }
    
    
    protected function registerBladeDirectives() {
		
		Blade::if('usercan', function ($permissions) {
			$authorized = false;
			$user = auth()->user();
			if ($user && $user->hasRole('administrator')) {
				$authorized = true;
			}
			else {
				foreach (explode('|', $permissions) as $p) {
					if ($user && $user->hasPermissionTo(trim($p))) {
						$authorized = true;
						break;
					}
				}
			}
			return $authorized;
		});
		
		Blade::if('useris', function ($ids) {
			$is = false;
			$user = auth()->user();
			if ($user) {
				foreach (explode('|', $ids) as $id) {
					if ($user->id === $id) {
						$is = true;
						break;
					}
				}
			}
			return $is;
		});
		
		Blade::if('userisandcan', function ($ids, $permissions) {
			
			$is = false;
			$user = auth()->user();
			if ($user) {
				foreach (explode('|', $ids) as $id) {
					if ($user->id === $id) {
						$is = true;
						break;
					}
				}
			}
			
			$authorized = false;
			$user = auth()->user();
			if ($user && $user->hasRole('administrator')) {
				$authorized = true;
			}
			else {
				foreach (explode('|', $permissions) as $p) {
					if ($user && $user->hasPermissionTo(trim($p))) {
						$authorized = true;
						break;
					}
				}
			}
			
			return ($is && $authorized);
		});
		
		Blade::if('authororcan', function ($ids, $editPermissions, $permissions) {
			
			$is = false;
			$user = auth()->user();
			if ($user) {
				foreach (explode('|', $ids) as $id) {
					if ($user->id === $id) {
						$is = true;
						break;
					}
				}
			}
			
			$editAuthorized = false;
			$user = auth()->user();
			if ($user && $user->hasRole('administrator')) {
				$editAuthorized = true;
			}
			else {
				foreach (explode('|', $editPermissions) as $p) {
					if ($user && $user->hasPermissionTo(trim($p))) {
						$editAuthorized = true;
						break;
					}
				}
			}
			
			$authorized = false;
			$user = auth()->user();
			if ($user && $user->hasRole('administrator')) {
				$authorized = true;
			}
			else {
				foreach (explode('|', $permissions) as $p) {
					if ($user && $user->hasPermissionTo(trim($p))) {
						$authorized = true;
						break;
					}
				}
			}
			
			
			return (($is && $editAuthorized) || $authorized);
		});
    }
  

}
