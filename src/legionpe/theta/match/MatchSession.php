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

namespace legionpe\theta\match;

use legionpe\theta\lang\Phrases;
use legionpe\theta\match\log\joinquit\PlayerJoinLogInfo;
use legionpe\theta\Session;
use pocketmine\Player;

abstract class MatchSession extends Session{
	/** @var MatchPlugin */
	private $main;
	/** @var bool */
	private $isPlayer;
	public function __construct(MatchPlugin $main, Player $player, array $loginData){
		parent::__construct($player, $loginData);
		$this->main = $main;
	}
	public function getMain(){
		return $this->main;
	}
	public function login($method){
		parent::login($method);
		$this->isPlayer = $this->getMain()->getState() === MatchPlugin::STATE_OPEN;
		$this->send(Phrases::MATCH_WHATISTHIS, [
			"match" => $this->getMain()->getInstanceId(),
			"role" => $this->translate($this->isPlayer() ? Phrases::MATCH_TERMS_PLAYER : Phrases::MATCH_TERMS_SPECTATOR)
		]);
		if($this->isPlayer){
			PlayerJoinLogInfo::get($this->getUid())->log($this->getMain(), ["name:" . $this->getPlayer()->getName()]);
		}
	}
	/**
	 * @return boolean
	 */
	public function isPlayer(){
		return $this->isPlayer;
	}
	/**
	 * @return boolean
	 */
	public function isSpectator(){
		return !$this->isPlayer;
	}
}
