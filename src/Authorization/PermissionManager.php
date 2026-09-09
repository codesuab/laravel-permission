<?php
namespace Codesuab\Permission\Authorization;
use Illuminate\Contracts\Auth\Authenticatable; use Illuminate\Support\Facades\Cache; use Codesuab\Permission\Models\Role; use Codesuab\Permission\Support\TeamContext;
class PermissionManager {
 public function team(int|string|null $id):static{app(TeamContext::class)->set($id);return $this;}
 public function currentTeamId():?int{return app(TeamContext::class)->id();}
 public function check(?Authenticatable $user,string $permission):bool{if(!$user)return false;if($this->isSuperAdmin($user))return true;foreach($this->permissions($user) as $granted)if($this->matches($granted,$permission))return true;return false;}
 public function any(?Authenticatable $user,array $permissions):bool{foreach($permissions as $p)if($this->check($user,$p))return true;return false;}
 public function all(?Authenticatable $user,array $permissions):bool{foreach($permissions as $p)if(!$this->check($user,$p))return false;return true;}
 public function hasRole(?Authenticatable $user,string $role):bool{if(!$user)return false;if($role==='*'&&$this->isSuperAdmin($user))return true;return $this->roleQuery($user)->where('slug',$role)->where('is_active',true)->exists();}
 public function hasAnyRole(?Authenticatable $user,array $roles):bool{foreach($roles as $r)if($this->hasRole($user,$r))return true;return false;}
 public function hasAllRoles(?Authenticatable $user,array $roles):bool{foreach($roles as $r)if(!$this->hasRole($user,$r))return false;return true;}
 public function permissions(Authenticatable $user):array{if(!config('permission.cache.enabled'))return $this->resolvePermissions($user);return $this->cache()->remember($this->cacheKey($user),config('permission.cache.ttl',3600),fn()=>$this->resolvePermissions($user));}
 public function roles(Authenticatable $user):array{return $this->roleQuery($user)->where('is_active',true)->pluck('slug')->unique()->values()->all();}
 public function clear(?Authenticatable $user):void{if($user&&config('permission.cache.enabled'))$this->cache()->forget($this->cacheKey($user));}
 public function clearRole(Role $role):void{$role->users()->get()->each(fn($u)=>$this->clear($u));}
 public function clearPermissionUsers(int $permissionId):void{$roleModel=config('permission.models.role');$roles=$roleModel::query()->whereHas('permissions',fn($q)=>$q->whereKey($permissionId))->with('users')->get();foreach($roles as $role)$role->users->each(fn($u)=>$this->clear($u));}
 protected function roleQuery(Authenticatable $user){$q=$user->roles();$team=$this->currentTeamId();if(config('permission.teams.enabled',true)&&$team!==null)$q->where(function($x)use($team){$x->wherePivot('team_id',$team)->orWherePivotNull('team_id');});return $q;}
 protected function resolvePermissions(Authenticatable $user):array{$roleIds=$this->roleQuery($user)->where('is_active',true)->pluck('roles.id');$team=$this->currentTeamId();$rolePerm=$this->permissionQuery()->whereHas('roles',function($q)use($roleIds,$team){$q->whereIn('roles.id',$roleIds);if(config('permission.teams.enabled',true)&&$team!==null)$q->where(function($x)use($team){$x->wherePivot('team_id',$team)->orWherePivotNull('team_id');});});$slugs=$rolePerm->pluck('slug');$direct=$user->directPermissions();if(config('permission.teams.enabled',true)&&$team!==null)$direct->where(function($q)use($team){$q->wherePivot('team_id',$team)->orWherePivotNull('team_id');});return $slugs->merge($direct->pluck('slug'))->unique()->values()->all();}
 protected function permissionQuery(){ $model=config('permission.models.permission'); return $model::query(); }
 protected function matches(string $granted,string $requested):bool{if($granted==='*'||$granted===$requested)return true;if(!str_contains($granted,'*'))return false;$pattern='/^'.str_replace(['\\*','\\?'],['.*','.'],preg_quote($granted,'/')).'$/';return (bool)preg_match($pattern,$requested);}
 protected function isSuperAdmin(Authenticatable $user):bool{return config('permission.super_admin.enabled',true)&&$this->hasRoleDirectly($user,config('permission.super_admin.role','super-admin'));}
 protected function hasRoleDirectly(Authenticatable $user,string $role):bool{return $this->roleQuery($user)->where('slug',$role)->where('is_active',true)->exists();}
 protected function cacheKey(Authenticatable $user):string{return config('permission.cache.prefix','permission').':user:'.$user->getAuthIdentifier().':team:'.($this->currentTeamId()??'global');}
 protected function cache(){return config('permission.cache.store')?Cache::store(config('permission.cache.store')):Cache::store();}
}
