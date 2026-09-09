<?php
namespace Codesuab\Permission\Models;
use Illuminate\Database\Eloquent\Model; use Illuminate\Database\Eloquent\Relations\BelongsToMany; use Illuminate\Support\Str;
class Role extends Model { protected $guarded=[]; protected $casts=['is_system'=>'boolean','is_active'=>'boolean'];
 public function permissions():BelongsToMany{return $this->belongsToMany(config('permission.models.permission'),config('permission.tables.role_permissions','role_permissions'));}
 public function users():BelongsToMany{return $this->belongsToMany(config('permission.user_model'),config('permission.tables.user_roles','user_roles'));}
 public static function createRole(string $name):static{return static::firstOrCreate(['slug'=>Str::slug($name)],['name'=>$name]);}
 public function allow(string|array $permissions, int|string|null $teamId=null):static{$ids=collect((array)$permissions)->map(fn($p)=>Permission::ensure($p)->id);$this->permissions()->syncWithoutDetaching($ids->mapWithKeys(fn($id)=>[$id=>['team_id'=>$teamId]])->all());app('permission')->clearRole($this);return $this;}
 public function deny(string|array $permissions,int|string|null $teamId=null):static{$ids=Permission::whereIn('slug',(array)$permissions)->pluck('id');$q=$this->permissions();if($teamId===null)$q->wherePivotNull('team_id');else$q->wherePivot('team_id',$teamId);$q->detach($ids);app('permission')->clearRole($this);return $this;}
 public function syncPermissions(array $permissions,int|string|null $teamId=null):static{$ids=collect($permissions)->map(fn($p)=>Permission::ensure($p)->id)->all();$q=$this->permissions();if($teamId===null)$q->wherePivotNull('team_id');else$q->wherePivot('team_id',$teamId);$q->sync($ids);app('permission')->clearRole($this);return $this;}
 public function hasPermission(string $permission):bool{$grants=$this->permissions()->pluck('slug');foreach($grants as $g){if($g==='*'||$g===$permission)return true;if(str_contains($g,'*')&&preg_match('/^'.str_replace('\\*','.*',preg_quote($g,'/')).'$/',$permission))return true;}return false;}
}
