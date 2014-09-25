<?php

namespace PhpParser;
use PhpParser\NodeVisitor\Matcher;

/**
 * Searches through the AST to find a node with the given specifications.
 *
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 */
class Finder
{
	private $ast;

	public static function create(array $ast) {
		return new self($ast);
	}

	public function __construct(array $ast) {
		$this->ast = $ast;
	}

	public function find($expr, Node $relativeNode = null) {
		$visitor = $this->createVisitor($expr);

		$traverser = new NodeTraverser();
		$traverser->addVisitor($visitor);
		$traverser->traverse(null === $relativeNode ? $this->ast : array($relativeNode));

		return $visitor->getMatches();
	}

	private function createVisitor($expr) {
		$attr = $this->parseExpr($expr);

		return new Matcher($attr['class'], $attr);
	}

	private function parseExpr($expr) {
		$class = 'PhpParser\\Node\\';

		$block = 'class';
		$attributes = array();
		for ($i=0,$c=strlen($expr); $i<$c; $i++) {
			switch ($char = $expr[$i]) {
				case '[':
					$block = 'attribute_key';
					$attributeKey = '';
					break;

				case '=':
					$block = 'attribute_value';
					$attributeValue = '';
					break;

				case ']':
					if (!isset($attributeKey, $attributeValue)) {
						throw new \InvalidArgumentException(sprintf('Unexpected "]" at position %d, no attribute key or value exists.', $i));
					}
					$attributes[$attributeKey] = $attributeValue;
					unset($attributeKey, $attributeValue);
					break;

				default:
					switch ($block) {
						case 'class':
							$class .= $char;
							break;

						case 'attribute_key':
							$attributeKey .= $char;
							break;

						case 'attribute_value':
							$attributeValue .= $char;
							break;

						default:
							throw new \InvalidArgumentException(sprintf('Expected block starting element like "[", but got "%s" at position %d.', $char, $i));
					}
			}
		}

		$attributes['class'] = $class;

		return $attributes;
	}
}