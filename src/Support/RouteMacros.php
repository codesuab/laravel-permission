<?php
namespace Codesuab\Permission\Support;
use Illuminate\Routing\PendingResourceRegistration; use Illuminate\Support\Facades\Route;
class RouteMacros { public static function register():void{
 Route::macro('can',fn(...$p)=>$this->middleware('permission:'.collect($p)->flatten()->filter()->implode(',')));
 Route::macro('canAll',fn(...$p)=>$this->middleware('permission.all:'.collect($p)->flatten()->filter()->implode(',')));
 Route::macro('role',fn(...$r)=>$this->middleware('role:'.collect($r)->flatten()->filter()->implode(',')));
 Route::macro('roleAll',fn(...$r)=>$this->middleware('role.all:'.collect($r)->flatten()->filter()->implode(',')));
 Route::macro('team',fn($parameter='team')=>$this->middleware('permission.team:'.$parameter));
 if(method_exists(PendingResourceRegistration::class,'macro')) PendingResourceRegistration::macro('can',function(string $resource){return $this->middleware('permission.resource:'.$resource);});
 }}
