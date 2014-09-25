<?php

namespace PhpParser;

/**
 * Interface for the ImmutableNodeTraverser.
 *
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 */
interface NodeTraversalCallback
{
    /**
     * Returns whether the given node's children should be traversed.
     *
     * Called in pre-order (before children)
     *
     * @return Boolean
     */
    function shouldTraverse(Node $node);

    /**
     * Visits a given node.
     *
     * Called in post-order (children first)
     *
     * @return void
     */
    function visit(Node $node);
}