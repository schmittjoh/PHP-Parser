<?php

class PHPParser_Tests_Node_Stmt_IfTest extends PHPUnit_Framework_TestCase
{
    public function testTraversal()
    {
        $if = new PHPParser_Node_Stmt_If(new PHPParser_Node_Expr_Variable('foo'));

        foreach ($if as $node) {
            var_dump($node);
        }
    }
}