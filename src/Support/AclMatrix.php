<?php
namespace Codesuab\Permission\Support;
use Codesuab\Permission\Models\Role; use Codesuab\Permission\Models\Permission;
class AclMatrix {
 public function build(?int $teamId=null):array{$roles=Role::orderBy('name')->get();$permissions=Permission::orderBy('group')->orderBy('name')->get();$matrix=[];foreach($roles as $role){$q=$role->permissions();if($teamId===null)$q->wherePivotNull('team_id');else$q->where(function($x)use($teamId){$x->wherePivot('team_id',$teamId)->orWherePivotNull('team_id');});$matrix[$role->slug]=$q->pluck('slug')->all();}return ['roles'=>$roles->map(fn($r)=>['id'=>$r->id,'name'=>$r->name,'slug'=>$r->slug])->all(),'permissions'=>$permissions->map(fn($p)=>['id'=>$p->id,'name'=>$p->name,'slug'=>$p->slug,'group'=>$p->group])->all(),'matrix'=>$matrix];}
 public function sync(Role $role,array $permissionSlugs,?int $teamId=null):void{$role->syncPermissions($permissionSlugs,$teamId);}
 public function set(Role $role,string $permission,bool $allowed,?int $teamId=null):void{if($allowed)$role->allow($permission,$teamId);else$role->deny($permission,$teamId);}
}
