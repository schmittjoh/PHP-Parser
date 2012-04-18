<?php

class PHPParser_NodeVisitor_Matcher extends PHPParser_NodeVisitorAbstract
{
	private $class;
	private $attributes;
	private $matches = array();

	public function __construct($class, array $attributes = array()) {
		$this->class = $class;
		$this->attributes = $attributes;
	}

	public function enterNode(PHPParser_Node $node) {
		if (!$node instanceof $this->class) {
			return;
		}

		foreach ($node->getSubNodeNames() as $name) {
			if (isset($this->attributes[$name])
					&& $this->attributes[$name] !== $node->$name) {
				return;
			}
		}

		$this->matches[] = $node;
	}

	public function getMatches() {
		return $this->matches;
	}
}