<?php
namespace Codesuab\Permission\Middleware;
use Closure; use Illuminate\Http\Request; use Symfony\Component\HttpFoundation\Response; use Codesuab\Permission\Support\TeamContext;
class TeamMiddleware {
 public function handle(Request $request, Closure $next): Response {
  if (!config('permission.teams.enabled',true)) return $next($request);
  $id=$request->route(config('permission.teams.route_parameter','team'));
  $id=is_object($id)?($id->getKey()??null):$id;
  if ($id===null) return $next($request);
  $user=$request->user();
  if (!$user) abort(401);
  if (config('permission.teams.require_membership',true) && method_exists($user,'teams') && !$user->teams()->whereKey($id)->exists()) abort(403,'You are not a member of this team.');
  app(TeamContext::class)->set($id);
  try{return $next($request);} finally{app(TeamContext::class)->clear();}
 }
}
