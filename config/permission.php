<?php
return [
 'user_model'=>env('PERMISSION_USER_MODEL',App\Models\User::class),
 'models'=>['role'=>Codesuab\Permission\Models\Role::class,'permission'=>Codesuab\Permission\Models\Permission::class,'team'=>Codesuab\Permission\Models\Team::class],
 'tables'=>['roles'=>'roles','permissions'=>'permissions','role_permissions'=>'role_permissions','user_roles'=>'user_roles','user_permissions'=>'user_permissions','teams'=>'teams','team_users'=>'team_users'],
 'super_admin'=>['enabled'=>true,'role'=>'super-admin'],
 'cache'=>['enabled'=>true,'store'=>null,'ttl'=>3600,'prefix'=>'codesuab.permission'],
 'inertia'=>['enabled'=>true,'share'=>'auth.permissions','roles_share'=>'auth.roles','team_share'=>'auth.team'],
 'teams'=>['enabled'=>true,'route_parameter'=>'team','require_membership'=>true,'current_team_attribute'=>'current_team_id','global_permissions'=>true],
 'policies'=>['enabled'=>true,'infer_resource_from_model_table'=>true,'map'=>[],'abilities'=>['viewAny'=>'view','view'=>'view','create'=>'create','update'=>'update','delete'=>'delete','restore'=>'restore','forceDelete'=>'force-delete']],
];
