<?php
namespace Codesuab\Permission\Traits;
use Illuminate\Database\Eloquent\Relations\BelongsToMany; use Codesuab\Permission\Models\Permission; use Codesuab\Permission\Models\Role;
trait HasPermissions {
 public function roles():BelongsToMany{return $this->belongsToMany(config('permission.models.role'),config('permission.tables.user_roles','user_roles'))->withPivot('team_id')->withTimestamps();}
 public function directPermissions():BelongsToMany{return $this->belongsToMany(config('permission.models.permission'),config('permission.tables.user_permissions','user_permissions'))->withPivot('team_id')->withTimestamps();}
 public function role(string|array $roles, int|string|null $teamId=null):static{$ids=Role::whereIn('slug',(array)$roles)->pluck('id');$this->roles()->syncWithoutDetaching($ids->mapWithKeys(fn($id)=>[$id=>['team_id'=>$teamId]])->all());app('permission')->clear($this);return $this;}
 public function assignRole(string|array $roles,int|string|null $teamId=null):static{return $this->role($roles,$teamId);}
 public function rolesSync(array $roles,int|string|null $teamId=null):static{$ids=Role::whereIn('slug',$roles)->pluck('id');$q=$this->roles();if($teamId===null)$q->wherePivotNull('team_id');else$q->wherePivot('team_id',$teamId);$q->sync($ids->mapWithKeys(fn($id)=>[$id=>['team_id'=>$teamId]])->all());app('permission')->clear($this);return $this;}
 public function syncRoles(array $roles,int|string|null $teamId=null):static{return $this->rolesSync($roles,$teamId);}
 public function removeRole(string|array $roles,int|string|null $teamId=null):static{$ids=Role::whereIn('slug',(array)$roles)->pluck('id');$q=$this->roles();if($teamId===null)$q->wherePivotNull('team_id');else$q->wherePivot('team_id',$teamId);$q->detach($ids);app('permission')->clear($this);return $this;}
 public function givePermissionTo(string|array $permissions,int|string|null $teamId=null):static{$ids=collect((array)$permissions)->map(fn($p)=>Permission::ensure($p)->id);$this->directPermissions()->syncWithoutDetaching($ids->mapWithKeys(fn($id)=>[$id=>['team_id'=>$teamId]])->all());app('permission')->clear($this);return $this;}
 public function revokePermissionTo(string|array $permissions,int|string|null $teamId=null):static{$ids=Permission::whereIn('slug',(array)$permissions)->pluck('id');$q=$this->directPermissions();if($teamId===null)$q->wherePivotNull('team_id');else$q->wherePivot('team_id',$teamId);$q->detach($ids);app('permission')->clear($this);return $this;}
 public function syncPermissions(array $permissions,int|string|null $teamId=null):static{$ids=collect($permissions)->map(fn($p)=>Permission::ensure($p)->id)->all();$q=$this->directPermissions();if($teamId===null)$q->wherePivotNull('team_id');else$q->wherePivot('team_id',$teamId);$q->sync(collect($ids)->mapWithKeys(fn($id)=>[$id=>['team_id'=>$teamId]])->all());app('permission')->clear($this);return $this;}
 public function hasRole(string $role):bool{return app('permission')->hasRole($this,$role);}
 public function hasAnyRole(array $roles):bool{return app('permission')->hasAnyRole($this,$roles);}
 public function hasAllRoles(array $roles):bool{return app('permission')->hasAllRoles($this,$roles);}
 public function hasPermission(string $permission):bool{return app('permission')->check($this,$permission);}
 public function canPermission(string $permission):bool{return $this->hasPermission($permission);}
 public function hasAnyPermission(array $permissions):bool{return app('permission')->any($this,$permissions);}
 public function hasAllPermissions(array $permissions):bool{return app('permission')->all($this,$permissions);}
 public function permissionSlugs():array{return app('permission')->permissions($this);}
 public function roleSlugs():array{return app('permission')->roles($this);}
 public function isSuperAdmin():bool{return app('permission')->hasRole($this,config('permission.super_admin.role','super-admin'));}
}
