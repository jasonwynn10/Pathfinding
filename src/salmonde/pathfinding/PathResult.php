<?php
declare(strict_types = 1);

namespace salmonde\pathfinding;

use salmonde\pathfinding\astar\Node;
use SplQueue;

class PathResult extends SplQueue {

	public function getNextPosition(): Node{
		return $this->dequeue();
	}
}
