<?php

declare(strict_types=1);

namespace salmonde\pathfinding\astar\selector;

use pocketmine\block\Block;

interface NeighbourSelector{

	/**
	 * @return Block[]
	 */
	public function getNeighbours(Block $block) : array;
}
