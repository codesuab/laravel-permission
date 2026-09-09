<?php
namespace Codesuab\Permission\Authorization;
use Illuminate\Contracts\Auth\Authenticatable; use Codesuab\Permission\Exceptions\PermissionDeniedException;
class PermissionAuthorizer { public function authorize(?Authenticatable $user,string $permission):void{if(!app('permission')->check($user,$permission))throw new PermissionDeniedException($permission);} public function check(?Authenticatable $user,string $permission):bool{return app('permission')->check($user,$permission);} }
