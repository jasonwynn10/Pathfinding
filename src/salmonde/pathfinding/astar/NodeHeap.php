<?php
declare(strict_types = 1);

namespace salmonde\pathfinding\astar;

use SplMinHeap;

/**
 * @template T of Node
 * @extends SplMinHeap<T>
 */
class NodeHeap extends SplMinHeap {

	protected function compare($node1, $node2): int{
		return (int) ($node2->getF() - $node1->getF());
	}
}
