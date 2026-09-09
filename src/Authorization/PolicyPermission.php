<?php
namespace Codesuab\Permission\Authorization;
use Illuminate\Contracts\Auth\Authenticatable;
class PolicyPermission {
 public function check(Authenticatable $user,string $ability,array $arguments=[]):?bool {
  if(!config('permission.policies.enabled',true))return null;
  if(app('permission')->check($user,$ability))return true;
  $model=$arguments[0]??null; if(is_array($model))$model=$model[0]??null;
  if(!is_object($model))return null;
  $suffix=config('permission.policies.abilities.'.$ability); if(!$suffix)return null;
  $map=config('permission.policies.map',[]); $resource=$map[$model::class]??null;
  if(!$resource&&config('permission.policies.infer_resource_from_model_table',true)&&method_exists($model,'getTable'))$resource=$model->getTable();
  if(!$resource)return null;
  return app('permission')->check($user,$resource.'.'.$suffix);
 }
}
