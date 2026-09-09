<?php
use Codesuab\Permission\Models\Team;
use Codesuab\Permission\Authorization\PermissionAuthorizer;
if(!function_exists('permission')){function permission(string $name):bool{return app('permission')->check(auth()->user(),$name);}}
if(!function_exists('permission_any')){function permission_any(array $names):bool{return app('permission')->any(auth()->user(),$names);}}
if(!function_exists('permission_all')){function permission_all(array $names):bool{return app('permission')->all(auth()->user(),$names);}}
if(!function_exists('role')){function role(string $name):bool{return app('permission')->hasRole(auth()->user(),$name);}}
if(!function_exists('team_permission')){function team_permission(int|string $teamId,string $name):bool{return app('permission')->team($teamId)->check(auth()->user(),$name);}}
if(!function_exists('team')){function team(int|string $id):Team{return Team::query()->findOrFail($id);}}
if(!function_exists('authorize_permission')){function authorize_permission(string $name):void{app(PermissionAuthorizer::class)->authorize(auth()->user(),$name);}}
