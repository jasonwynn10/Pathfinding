<?php
declare(strict_types=1);

namespace salmonde\pathfinding\astar;

use pocketmine\math\Vector3;
use const PHP_INT_MAX;

class Node extends Vector3{

	public function __construct(float $x, float $y, float $z, private ?Node $predecessor = null, private float $g = PHP_INT_MAX, private float $h = PHP_INT_MAX){
		parent::__construct($x, $y, $z);
	}

	public static function fromVector3(Vector3 $pos) : self{
		return new self($pos->x, $pos->y, $pos->z);
	}

	public function getF() : float{
		return $this->g + $this->h;
	}

	public function getG() : float{
		return $this->g;
	}

	public function setG(float $g) : self{
		$this->g = $g;
		return $this;
	}

	public function getH() : float{
		return $this->h;
	}

	public function setH(float $h) : self{
		$this->h = $h;
		return $this;
	}

	public function getPredecessor() : ?Node{
		return $this->predecessor;
	}

	public function setPredecessor(?Node $node) : self{
		$this->predecessor = $node;
		return $this;
	}
}
