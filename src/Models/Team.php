<?php
namespace Codesuab\Permission\Models;
use Illuminate\Database\Eloquent\Model; use Illuminate\Database\Eloquent\Relations\BelongsToMany; use Illuminate\Support\Str;
class Team extends Model { protected $guarded=[]; protected $casts=['meta'=>'array','is_active'=>'boolean'];
 public function users():BelongsToMany{return $this->belongsToMany(config('permission.user_model'),config('permission.tables.team_users','team_users'))->withTimestamps();}
 public static function createTeam(string $name,?int $ownerId=null):static{return static::firstOrCreate(['slug'=>Str::slug($name)],['name'=>$name,'owner_id'=>$ownerId]);}
}
