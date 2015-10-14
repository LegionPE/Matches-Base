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

namespace legionpe\theta\match\query;

use legionpe\theta\match\match\MatchConfiguration;
use legionpe\theta\match\MatchPlugin;
use legionpe\theta\query\NextIdQuery;
use pocketmine\Server;

class StartMatchQuery extends NextIdQuery{
	/** @var int */
	private $config;
	public function __construct(MatchPlugin $main, MatchConfiguration $config){
		$this->config = $main->storeObject($config);
		parent::__construct($main, self::MATCH);
	}
	public function onCompletion(Server $server){
		$main = MatchPlugin::getInstance($server);
		/** @var MatchConfiguration $config */
		$config = $main->fetchObject($this->config);
		$main->addMatch($this->getId(), $config);
	}
}
