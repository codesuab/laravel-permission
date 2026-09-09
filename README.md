# Codesuab Laravel Permission

Secure, framework-native RBAC + ACL for Laravel 11/12/13 with Blade, Gate/Policy integration, Inertia React, teams/tenancy, wildcard permissions, route/resource protection and an ACL matrix API.

## Install
```bash
composer require codesuab/laravel-permission
php artisan permission:install
php artisan migrate
php artisan permission:seed
```

Add traits to your User model:
```php
use Codesuab\Permission\Traits\HasPermissions;
use Codesuab\Permission\Traits\HasTeams;

class User extends Authenticatable
{
    use HasPermissions, HasTeams;
}
```

## Permissions
```php
use Codesuab\Permission\Models\Permission;
use Codesuab\Permission\Models\Role;

Permission::ensure('users.view');
Permission::crud('users');

Role::createRole('manager')->allow([
    'users.view', 'users.create', 'users.update',
]);

$user->assignRole('manager');
$user->givePermissionTo('reports.export');

$user->hasPermission('users.view');
$user->hasRole('manager');
```

`Permission::ensure()` intentionally replaces `Permission::create()` so Eloquent's native `Model::create()` is never overridden.

## Routes
```php
Route::get('/users', ...)->can('users.view');
Route::get('/reports', ...)->canAll('reports.view', 'reports.export');
Route::get('/admin', ...)->role('admin');
Route::resource('users', UserController::class)->can('users');
```

Resource mapping:
`index/show=view`, `create/store=create`, `edit/update=update`, `destroy=delete`.

Team-scoped routes:
```php
Route::middleware('permission.team')->group(function () {
    Route::resource('/teams/{team}/users', TeamUserController::class)->can('users');
});
```
Or:
```php
Route::middleware('permission.team')->get('/teams/{team}/dashboard', ...);
```

## Policy / Gate integration

The package registers a `Gate::before` integration. Explicit permission abilities work automatically:
```php
$this->authorize('users.update', $user);
Gate::allows('users.update', $user);
```

Standard Laravel Policy abilities are translated to CRUD permissions. For a `User` model:
```php
$this->authorize('update', $user); // checks users.update
$this->authorize('view', $user);   // checks users.view
$this->authorize('delete', $user); // checks users.delete
```

The resource name is inferred from `$model->getTable()`. Override it in `config/permission.php`:
```php
'policies' => [
    'map' => [App\\Models\\Invoice::class => 'billing.invoices'],
],
```
Then `update Invoice` checks `billing.invoices.update`.

Normal Laravel Policy logic remains available: if the package does not grant the ability, Gate continues to the registered policy.

## Teams / Multi-tenancy

A permission can be global or team-scoped. User-role and direct-user-permission assignments contain `team_id`.

```php
$team = Team::createTeam('Acme', $owner->id);
$owner->addToTeam($team);
$user->addToTeam($team);

$user->assignRole('manager', $team->id);
$user->givePermissionTo('billing.refund', $team->id);

app('permission')->team($team->id)->check($user, 'billing.refund');
```

Team middleware reads `{team}` from the route, verifies membership when enabled, sets the request team context, and clears it after the request.

Global grants remain available inside a team. Team grants are isolated by `team_id`.

## ACL Matrix

```php
use Codesuab\Permission\Support\AclMatrix;

$data = app(AclMatrix::class)->build($teamId);
```

The result contains:
- roles
- permissions grouped by permission group
- `matrix[role_slug] => permission_slugs[]`

Update a role:
```php
app(AclMatrix::class)->sync(
    $role,
    ['users.view', 'users.update'],
    $teamId
);

app(AclMatrix::class)->set($role, 'users.delete', true, $teamId);
```

React includes an optional matrix component:
```tsx
import { AclMatrix } from '@codesuab/laravel-permission';
<AclMatrix data={matrix} onChange={...} />
```

## Blade
```blade
@permission('users.view')
    <a href="/users">Users</a>
@endpermission

@role('admin')
    ...
@endrole
```

## Inertia React
The backend shares `auth.permissions`, `auth.roles` and `auth.team` automatically.

```tsx
import { Can, usePermissions } from '@codesuab/laravel-permission';

const { can, canAny, hasRole } = usePermissions();

<Can permission="users.create">
    <CreateUserButton />
</Can>
```

Client-side checks are UI helpers only. Authorization must always be enforced by Laravel middleware/Gate/Policy.

## Wildcards
```php
Role::createRole('manager')->allow('users.*');
Role::createRole('root')->allow('*');
```

## Super admin
The configured `super-admin` role bypasses permission checks. Configure it with:
```php
'super_admin' => [
    'enabled' => true,
    'role' => 'super-admin',
],
```

## Security model
- Server-side middleware and Gate/Policy are authoritative.
- Direct permissions are included in authorization and Inertia data.
- Team context is part of the permission cache key.
- Global permissions can be combined with tenant-scoped grants.
- No React/Blade check is treated as a security boundary.

## License
MIT
