<?php
namespace Codesuab\Permission;
use Illuminate\Support\ServiceProvider; use Illuminate\Support\Facades\Gate; use Illuminate\Support\Facades\Blade; use Codesuab\Permission\Authorization\PermissionManager; use Codesuab\Permission\Authorization\PermissionAuthorizer; use Codesuab\Permission\Authorization\PolicyPermission; use Codesuab\Permission\Support\TeamContext; use Codesuab\Permission\Support\AclMatrix; use Codesuab\Permission\Middleware\TeamMiddleware;
class PermissionServiceProvider extends ServiceProvider {
 public function register():void{ $this->mergeConfigFrom(__DIR__.'/../config/permission.php','permission');$this->app->singleton(PermissionManager::class);$this->app->singleton(PermissionAuthorizer::class);$this->app->alias(PermissionManager::class,'permission');$this->app->singleton(TeamContext::class);$this->app->alias(TeamContext::class,'permission.team.context');$this->app->singleton(PolicyPermission::class);$this->app->singleton(AclMatrix::class); }
 public function boot():void{
  $this->publishes([__DIR__.'/../config/permission.php'=>config_path('permission.php')],'permission-config');$this->loadMigrationsFrom(__DIR__.'/../database/migrations');
  $router=$this->app['router'];$router->aliasMiddleware('permission.team',TeamMiddleware::class);$router->aliasMiddleware('permission',Middleware\PermissionMiddleware::class);$router->aliasMiddleware('permission.all',Middleware\PermissionAllMiddleware::class);$router->aliasMiddleware('role',Middleware\RoleMiddleware::class);$router->aliasMiddleware('role.all',Middleware\RoleAllMiddleware::class);$router->aliasMiddleware('permission.resource',Middleware\ResourcePermissionMiddleware::class);
  Gate::before(function($user,$ability,$arguments=[]){$r=app(PolicyPermission::class)->check($user,$ability,(array)$arguments);return $r===true?true:null;});
  Blade::if('permission',fn($p)=>app('permission')->check(auth()->user(),$p));Blade::if('anypermission',fn(...$p)=>app('permission')->any(auth()->user(),$p));Blade::if('allpermissions',fn(...$p)=>app('permission')->all(auth()->user(),$p));Blade::if('role',fn($r)=>app('permission')->hasRole(auth()->user(),$r));Blade::if('anyrole',fn(...$r)=>app('permission')->hasAnyRole(auth()->user(),$r));Blade::if('allroles',fn(...$r)=>app('permission')->hasAllRoles(auth()->user(),$r));
  Support\RouteMacros::register();Support\InertiaShare::register();
 }
}
