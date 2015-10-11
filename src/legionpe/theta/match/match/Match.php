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

namespace legionpe\theta\match\match;

use legionpe\theta\config\Settings;
use legionpe\theta\match\log\joinquit\PlayerJoinLogInfo;
use legionpe\theta\match\log\joinquit\SpectatorJoinLogInfo;
use legionpe\theta\match\log\system\StartOpenLogInfo;
use legionpe\theta\match\MatchPlugin;
use legionpe\theta\match\MatchSession;

class Match{
	const STATE_OPEN = 0;
	const STATE_PREPARING = 1;
	const STATE_RUNNING = 2;
	const STATE_CLOSING = 3;
	/** @var MatchPlugin */
	private $main;
	/** @var int */
	private $instanceId;
	/** @var int */
	private $state = self::STATE_OPEN;
	/** @var MatchSession[] */
	private $sessions = [];

	public function __construct(MatchPlugin $main, $instanceId){
		$this->main = $main;
		$this->instanceId = $instanceId;
		$this->getMain()->getLogger()->notice("Instance ID: $this->instanceId");
		StartOpenLogInfo::get(Settings::$LOCALIZE_IP, Settings::$LOCALIZE_PORT, Settings::$SYSTEM_IS_TEST)->log($this->getMain());
	}
	/**
	 * @return MatchPlugin
	 */
	public function getMain(){
		return $this->main;
	}
	/**
	 * @return int
	 */
	public function getInstanceId(){
		return $this->instanceId;
	}
	/**
	 * @return int
	 */
	public function getState(){
		return $this->state;
	}
	public function isOpen(){
		return $this->state === self::STATE_OPEN;
	}
	public function isClosed(){
		return $this->state === self::STATE_CLOSING;
	}
	/**
	 * This is the API function.
	 * @param MatchSession $session
	 * @param bool $isPlayer
	 * @return bool
	 */
	public function join(MatchSession $session, $isPlayer){
		if($isPlayer and !$this->isOpen() or $this->isClosed()){
			return false;
		}
		$this->sessions[$session->getUid()] = true;
		$session->setMatch($this, $isPlayer);
		$cnt = count($this->sessions);
		if($isPlayer){
			PlayerJoinLogInfo::get($session->getUid(), $cnt)->log($this->getMain());
		}else{
			SpectatorJoinLogInfo::get($session->getUid())->log($this->getMain());
		}
		return true;
	}
}
