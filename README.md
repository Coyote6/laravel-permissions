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

## Policy Traits
You can now use the StandardPolicy, StandardAuthorPolicy, StandardClientPolicy, and StandardAuthorOrClientPolicy to make short work out of writing policies for your models.

The policy traits follow a standard method for using permission names to check a user's permissions. The permission names for the StandardPolicy should follow this convention with the [PLURAL_MODEL_NAME] being replaced by lower cased plural form of your model.
administer_[PLURAL_MODEL_NAME]
create_[PLURAL_MODEL_NAME]
update_[PLURAL_MODEL_NAME]
delete_[PLURAL_MODEL_NAME]
view_[PLURAL_MODEL_NAME]
search_[PLURAL_MODEL_NAME]

The StandardAuthorPolicy expands on the StandardPolicy and allows the user to always access their own model permissions.

The StandardClientPolicy expands on the StandardPolicy but also adds the following permissions and checks if the user's client id matches that of the model's:
administer_client_[PLURAL_MODEL_NAME]
update_client_[PLURAL_MODEL_NAME]
delete_client_[PLURAL_MODEL_NAME]
view_client_[PLURAL_MODEL_NAME]
search_client_[PLURAL_MODEL_NAME]

The StandardAuthorOrClientPolicy combines the StandardAuthorPolicy with the StandardClient Policy allowing a user permission to access the model if they are the owner or it is owned by their client.

### Using Policy Traits
To use these Policy traits, you attach the trait to your policy and write the name of the permission in the $modelPermissionName property.  This will be the same [PLURAL_MODEL_NAME] you used when writing your permissions.

```php
<?php


namespace App\Policies;

use App\Models\User;
use App\Models\Model;
use App\Traits\StandardPolicy;
use Illuminate\Auth\Access\HandlesAuthorization;


class ModelPolicy {


	use StandardPolicy,
		HandlesAuthorization;
		
	protected string $modelPermissionName = 'models';

}
```


When the policy calls one of these 6 actions using the authorize() method, it generates a check on the permission for the action using one of those 6 permissions on the model.  So if the $modelPermissionName property is set to 'models' when authorize() method has its action set, these are the permission names it checks:
```php
$this->authorize('administer', $model) - administer_models
$this->authorize('viewCrud', $model) - administer_models
$this->authorize('viewUserCrud', $model) - view_models
$this->authorize('search', $model) - search_models
$this->authorize('view', $model') - view_models
$this->authorize('create' $model) - create_models
$this->authorize('update', $model) - update_models
$this->authorize('delete', $model) - delete_models
```

### Optional Parameters

#### Model Type Check ($autoDetectPolicy & $modelClass) - All Policy Traits
The StandardPolicy trait has two additional parameters that allow you to check if the provided model in the authorize() method matches what is expected in the policy.  These are unnecessary if you are using Laravel's autodetect policy, but may be a good idea to turn one if manually mapping policies, but definitely not required.  They are here just to prevent accidentally mapping the wrong policy to a model and helps prevent possible security bugs by allowing the incorrect permissions.

The first step is setting the autodetect on the policy to false.
```php
protected bool $autoDetectPolicy = false;
```
Any other value besides false, will result in $this->autoDetectPolicy reverting to the default of true, and no longer checking the model.

The next is to set the policy's $modelClass property. This should be the full namespace and class name of the model being checked.
```php
protected string $modelClass = Model::class;
```
or 
```php
protected string $modelClass = 'App\Models\Model';
```

#### Model Owner ($modelOwnerProperty) - StandardAuthorPolicy & StandandAuthorOrClientPolicy
The model owner property allows you to change the owner of a model, if you use something besides the default of author_id, to relate to the $user->id.

Examples:
```php
// $model->user_id is a reference to $user->id
protected string $modelOwnerProperty = 'user_id';
```

```php
// $model->owner_id is a reference to $user->id
protected string $modelOwnerProperty = 'owner_id';
```

#### Model Client ($modelClientProperty) - StandardClientPolicy & StandandAuthorOrClientPolicy
The client owner property allows you to change the client id of a model, if you use something besides the default of client_id, to relate to the $user->client_id.

Examples:
```php
// $model->client is a reference to $user->client
protected string $modelClientProperty = 'client';
```

```php
// $model->team_id is a reference to $user->team_id
protected string $modelClientProperty = 'team_id';
```

#### Overriding Permission Names
If you need to change the default permission prefixes, you can do so using their property names as follows:
```php
protected string $administerPrefix = 'administer_';
protected string $viewPrefix = 'view_';
protected string $searchPrefix = 'search_';
protected string $createPrefix = 'create_';
protected string $updatePrefix = 'update_';
protected string $deletePrefix = 'delete_';

// Client Policy Prefixes
protected string $administerClientPrefix = 'administer_client_';
protected string $viewClientPrefix = 'view_client_';
protected string $searchClientPrefix = 'search_client_';
protected string $updateClientPrefix = 'update_client_';
protected string $deleteClientPrefix = 'delete_client_';

```

Or you can override their getter methods:
```php
$this->getAdministerPrefix();
$this->getViewPrefix();
$this->getSearchPrefix();
$this->getCreatePrefix();
$this->getUpdatePrefix();
$this->getDeletePrefix();


// Client Policy Prefix Methods
$this->getAdministerClientPrefix();
$this->getViewClientPrefix();
$this->getSearchClientPrefix();
$this->getUpdateClientPrefix();
$this->getDeleteClientPrefix();

```

Example:
```php
protected string $updatePrefix = 'edit_';
```

This will change the permission that is being checked from 'update_models' to 'edit_models'.