<?php
declare(strict_types = 1);

namespace salmonde\pathfinding\astar;

use pocketmine\block\Block;
use pocketmine\block\BlockLegacyIds as Ids;

class DefaultCostCalculator extends CostCalculator {

	public function getCost(Block $block): float{
		return match ($block->getId()) {
			Ids::WATER, Ids::FLOWING_WATER => 2.0,
			Ids::COBWEB => 3.0,
			default => 1.0,
		};
	}
}
