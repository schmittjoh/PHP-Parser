<?php

namespace PhpParser;

/**
 * Alternative Implementation to the default NodeTraverser.
 *
 * This implementation is faster, but you cannot rewrite the AST.
 * Use this implementation when solely conducting analysis on the AST.
 *
 * This implementation also only supports one callback at a time.
 *
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 */
class CallbackNodeTraverser
{
    private $callback;

    public static function traverseWithCallback(array $nodes, NodeTraversalCallback $callback) {
        $traverser = new self($callback);
        $traverser->traverse($nodes);
    }

    public function __construct(NodeTraversalCallback $callback) {
        $this->callback = $callback;
    }

    public function traverse(array $nodes) {
        foreach ($nodes as $node) {
            if (is_array($node)) {
                $this->traverse($node);
            }

            if (!$node instanceof Node) {
                continue;
            }

            if ($this->callback->shouldTraverse($node)) {
                $this->traverse(iterator_to_array($node, false));
            }

            $this->callback->visit($node);
        }
    }
}