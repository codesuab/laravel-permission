<?php
namespace Codesuab\Permission\Support;
class InertiaShare { public static function register():void{if(!config('permission.inertia.enabled')||!class_exists(\Inertia\Inertia::class))return;
 \Inertia\Inertia::share(config('permission.inertia.share','auth.permissions'),fn()=>auth()->check()?app('permission')->permissions(auth()->user()):[]);
 \Inertia\Inertia::share(config('permission.inertia.roles_share','auth.roles'),fn()=>auth()->check()?app('permission')->roles(auth()->user()):[]);
 \Inertia\Inertia::share(config('permission.inertia.team_share','auth.team'),fn()=>app(TeamContext::class)->id());
 }}
