# Laravel Permissions

A package for managing permissions and user roles.

## To use
1. Install require coyote6/laravel-permissions
2. Run migrations (and seed if you want the default values) 
```
php artisan migrate
```
Or
```
php artisan migrate --seed
```
Or
```
db::seed --class=Coyote6\LaravelPermissions\Resources\Databases\Seeders\PermissionsSeeder
```
3. Add traits to the User model.
```php
use Coyote6\LaravelPermissions\Traits\HasRoles;
use Coyote6\LaravelPermissions\Traits\UserRoles;

class User extends Authenticatable {

	// Add the traits.
	use HasRoles,
		UserRoles;

}
```
4. Add traits to all needed policies.
```php
use Coyote6\LaravelPermissions\Traits\AdminAccessTrait;

class ExamplePolicy {
	use AdminAccessTrait;
}	
```
5. Add admin role to your admin user(s) via code:
```php
$u = User::find(1);
$u->addRole('administrator');
```
(Working on a better method)

6. Once your admin user has the administrator role you and if using the coyote6/laravel-crud package, go to the '/admin/users' on your site and manage any additional roles from there.

7. To create permissions and roles, and add permissions to roles you can use the following code example:
```php
use Coyote6\LaravelPermissions\Models\Permissions;
use Coyote6\LaravelPermissions\Models\Models;

$permission = Permission::create (['name' => 'Name of Permission']); // A machine name id will automatically be created.
$role = Role::create (['name' => 'Name of Permission']); // A machine name id will automatically be created.

$role->addPermission($permission);

```
Or if using the coyote6/laravel-crud package, go to the a '/admin/users/permissions' and '/admin/users/roles' respectively on the site.

8. Add permission code to policies:
```php 
class ExamplePolicy {


	use AdminAccessTrait,
		HandlesAuthorization;
	
	
	public function view (User $user) {
		
		//
		// Example using the machine name id for the permission
		//
		if ($user->hasPermission ('do_something')) {
			return true;
		}
		return false;
	}
	
	
	public function create (User $user) {
		
		//
		// Example using the name for the permission
		//
		if ($user->hasPermission ('Create Example', 'name')) {
			return true;
		}
		return false;
	}
}
```
9. Add permission code as needed to templates via blade directives.

## Blade Directives

### User Can - Permission Id(s)
```php
<div>
	@usercan ('do_something')
		<p>Show if user can do something</p>
	@endusercan
</div>
```
```php
<div>
	@usercan ('do_something|do_something_else')
		<p>Show if user can do something or do something else</p>
	@endusercan
</div>
```
### User Is - User Id(s)
```php
<div>
	@useris (1)
		<p>Show if user is user - 1</p>
	@enduseris
</div>
```
```php
<div>
	@useris ('1|2|3')
		<p>Show if user is user - 1, 2, or 3</p>
	@enduseris
</div>
```
### User Is and Can - User Id(s) and Permission Id(s)
```php
<div>
	@userisandcan (1, 'do_something')
		<p>Show if user is user - 1</p>
	@enduserisandcan
</div>
```
```php
<div>
	@userisandcan ('1|2|3', 'do_something|do_something_else')
		<p>Show if user is user - 1,2, or 3 and can do something or can do something else</p>
	@enduserisandcan
</div>
```
### Author Or Can - User Id(s) and Has Permission or Has Other Permission
```php
<div>
	@authororcan (1, 'do_something', 'do_something_else')
		<p>Show if user is user - 1 and can do something or the user is any user who can do something else</p>
	@endauthororcan
</div>
```
```php
<div>
	@authororcan ('1|2|3', 'do_something|do_something_else', 'do_some_other_thing_1|do_some_other_thing_2')
		<p>Show if user is user - 1,2, or 3 and can do something or can do something else or if the user is any user who can do some other thing 1 or 2.</p>
	@endauthororcan
</div>
```