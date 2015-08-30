<?php

/*
 * LegionPE
 *
 * Copyright (C) 2015 PEMapModder and contributors
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author PEMapModder
 */

namespace legionpe\theta\match\arena;

use pocketmine\block\Block;
use pocketmine\level\format\Chunk;
use pocketmine\level\format\FullChunk;
use pocketmine\level\Level;
use pocketmine\level\Position;

class LevelWrite{
	public $x;
	public $y;
	public $z;
	public $oldId;
	public $oldDamage;
	public $newId;
	public $newDamage;
	/**
	 * @param Level $level
	 * @return Block
	 */
	public function newBlock(Level $level){
		return Block::get($this->newId, $this->newDamage, new Position($this->x, $this->y, $this->z, $level));
	}
	/**
	 * @param Level $level
	 * @return Chunk|FullChunk
	 */
	public function getChunk(Level $level){
		return $level->getChunk($this->x >> 4, $this->z >> 4);
	}
	public function getChunkHash(){
		return PHP_INT_SIZE === 8 ? (($this->x & 0xFFFFFFFF) << 32) | ($this->z & 0xFFFFFFFF) : $this->x . ":" . $this->z;
	}
	public function getBlockHash(){
		return PHP_INT_SIZE === 8 ? (($this->x & 0xFFFFFFF) << 35) | (($this->y & 0x7f) << 28) | ($this->z & 0xFFFFFFF) : $this->x . ":" . $this->y .":". $this->z;
	}
}
