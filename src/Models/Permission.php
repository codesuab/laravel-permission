<?php
namespace Codesuab\Permission\Models;
use Illuminate\Database\Eloquent\Model; use Illuminate\Database\Eloquent\Relations\BelongsToMany; use Illuminate\Support\Str;
class Permission extends Model { protected $guarded=[]; protected $casts=['is_system'=>'boolean'];
 public function roles():BelongsToMany{return $this->belongsToMany(config('permission.models.role'),config('permission.tables.role_permissions','role_permissions'));}
 public function users():BelongsToMany{return $this->belongsToMany(config('permission.user_model'),config('permission.tables.user_permissions','user_permissions'));}
 public static function ensure(string $permission):static{$slug=Str::of($permission)->trim()->lower()->toString();return static::firstOrCreate(['slug'=>$slug],['name'=>Str::of($slug)->replace(['.','-','_'],' ')->title()->toString(),'group'=>Str::before($slug.'.',$slug)]);}
 public static function ensureMany(array $permissions):array{return collect($permissions)->map(fn($p)=>static::ensure($p))->all();}
 public static function crud(string $resource):array{return ['view'=>$resource.'.view','create'=>$resource.'.create','update'=>$resource.'.update','delete'=>$resource.'.delete'];}
}
