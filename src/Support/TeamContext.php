<?php
namespace Codesuab\Permission\Support;
class TeamContext {
 private ?int $id=null;
 public function set(int|string|null $id):void{$this->id=$id===null?null:(int)$id;}
 public function id():?int{return $this->id;}
 public function clear():void{$this->id=null;}
 public function active():bool{return $this->id!==null;}
}
