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

use legionpe\theta\BasePlugin;
use legionpe\theta\config\Settings;
use legionpe\theta\match\log\system\StartOpenLogInfo;
use legionpe\theta\query\NextIdQuery;

abstract class MatchPlugin extends BasePlugin{
	const STATE_OPEN = 0;
	const STATE_PREPARING = 1;
	const STATE_RUNNING = 2;
	const STATE_CLOSING = 3;
	private $instanceId;
	private $state = self::STATE_OPEN;
	public function onEnable(){
		parent::onEnable();
		$this->getLogger()->notice("Fetching instance ID...");
		$name = NextIdQuery::MATCH;
		$db = $this->getDb();
		$db->begin_transaction();
		$result = $db->query("SELECT value+1 AS result FROM ids WHERE name='$name' FOR UPDATE");
		$this->getDb()->query("UPDATE ids SET value=value+1 WHERE name='$name'");
		$this->getDb()->commit();
		$this->instanceId = (int) $result->fetch_assoc()["result"];
		$result->close();
		$this->getLogger()->notice("Instance ID: $this->instanceId");
		StartOpenLogInfo::get(Settings::$LOCALIZE_IP, Settings::$LOCALIZE_PORT, Settings::$SYSTEM_IS_TEST)->log($this);
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
}
