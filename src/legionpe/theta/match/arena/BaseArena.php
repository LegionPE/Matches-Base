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

use legionpe\theta\match\BaseMatch;
use legionpe\theta\match\MatchSession;
use pocketmine\level\format\FullChunk;
use pocketmine\level\Level;
use pocketmine\network\protocol\UpdateBlockPacket;
use pocketmine\Player;

abstract class BaseArena{
	/** @var Level */
	private $level;
	/** @var LevelWrite[] */
	private $write = [];
	/** @var BaseMatch|null */
	private $match = null;
	/** @var MatchSession[] */
	private $viewers = [];
	protected function __construct(Level $level){
		$this->level = $level;
	}
	/**
	 * @return BaseMatch|null
	 */
	public function getMatch(){
		return $this->match;
	}
	public function getViewers(){
		return $this->viewers;
	}
	public function addViewer(MatchSession $session, $send = true){
		$this->viewers[$session->getUid()] = $session;
		if($send){
			$this->sendChanges([$session->getPlayer()]);
		}
	}
	/**
	 * @param MatchSession[] $sessions
	 */
	public function addViewers(array $sessions){
		$players = [];
		foreach($sessions as $session){
			$this->addViewer($session, false);
			$players[] = $session->getPlayer();
		}
		$this->sendChanges($players);
	}
	public function removeViewer(MatchSession $session){
		$this->sendReal($session);
		unset($this->viewers[$session->getUid()]);
	}
	/**
	 * @param Player[] $players
	 */
	public function sendChanges(array $players){
		$this->level->sendBlocks($players, array_map(function(LevelWrite $write){
			return $write->newBlock($this->level);
		}, $this->write), UpdateBlockPacket::FLAG_ALL_PRIORITY, true);
	}
	public function sendReal(MatchSession $session){
		/** @var FullChunk[] $chunks */
		$chunks = [];
		foreach($this->write as $write){
			$hash = $write->getChunkHash();
			if(!isset($chunks[$hash])){
				$chunks[$hash] = true;
			}
		}
		$player = $session->getPlayer();
		foreach($chunks as $chunk => $v){
			Level::getXZ($chunk, $X, $Z);
			$this->level->requestChunk($X, $Z, $player);
		}
	}
}
