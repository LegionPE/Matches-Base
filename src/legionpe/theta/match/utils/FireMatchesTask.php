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

namespace legionpe\theta\match\utils;

use legionpe\theta\match\MatchPlugin;
use legionpe\theta\match\query\StartMatchQuery;
use pocketmine\scheduler\PluginTask;

class FireMatchesTask extends PluginTask{
	public function onRun($currentTick){
		/** @var MatchPlugin $main */
		$main = $this->getOwner();
		foreach($main->getMatches() as $id => $match){
			if($match->isOpen()){
				return;
			}
		}
		new StartMatchQuery($main);
	}
}
