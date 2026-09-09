<?php
namespace Codesuab\Permission\Traits;
use Illuminate\Database\Eloquent\Relations\BelongsToMany; use Codesuab\Permission\Models\Team;
trait HasTeams {
 public function teams(): BelongsToMany { return $this->belongsToMany(Team::class,config('permission.tables.team_users','team_users'))->withTimestamps(); }
 public function belongsToTeam(int|Team $team):bool { $id=$team instanceof Team?$team->getKey():$team; return $this->teams()->whereKey($id)->exists(); }
 public function addToTeam(int|Team $team):static { $id=$team instanceof Team?$team->getKey():$team; $this->teams()->syncWithoutDetaching([$id]); return $this; }
 public function leaveTeam(int|Team $team):static { $id=$team instanceof Team?$team->getKey():$team; $this->teams()->detach($id); return $this; }
 public function currentTeamId():?int { $attr=config('permission.teams.current_team_attribute','current_team_id'); return isset($this->{$attr})?(int)$this->{$attr}:app('permission.team.context')->id(); }
}
