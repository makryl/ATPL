<?php

namespace aeqdev\ATPLTest;

class Layout1Base extends \aeqdev\ATPL
{

    function __construct()
    {
        echo "#construct Layout1Base\n";
        $this->addViewFile(__DIR__ . '/Layout1Base.php');
    }

    function render()
    {
        echo "#render Layout1Base\n";
        parent::render();
    }

}

class Layout1 extends Layout1Base
{

    function __construct()
    {
        echo "#construct Layout1\n";
        $this->addViewFile(__DIR__ . '/Layout1.php');
        parent::__construct();
    }

    function render()
    {
        echo "#render Layout1\n";
        parent::render();
    }

}

class Layout1Inside extends Layout1
{

    function __construct()
    {
        echo "#construct Layout1Inside\n";
        parent::__construct();
        $this->addViewFile(__DIR__ . '/Layout1Inside.php');
    }

    function render()
    {
        echo "#render Layout1Inside\n";
        parent::render();
    }

}

class Layout2 extends \aeqdev\ATPL
{

    function __construct()
    {
        echo "#construct Layout2\n";
        $this->addViewFile(__DIR__ . '/Layout2.php');
    }

    function render()
    {
        echo "#render Layout2\n";
        parent::render();
    }

}



class ContentBase extends \aeqdev\ATPL
{

    function __construct()
    {
        echo "#construct ContentBase\n";
        $this->addViewFile(__DIR__ . '/ContentBase.php');
    }

    function render()
    {
        echo "#render ContentBase\n";
        parent::render();
    }

}

class Content extends ContentBase
{

    function __construct()
    {
        echo "#construct Content\n";
        $this->addViewFile(__DIR__ . '/Content.php');
        parent::__construct();
    }

    function render()
    {
        echo "#render Content\n";
        parent::render();
    }

}

class Child1 extends \aeqdev\ATPL
{

    function __construct()
    {
        echo "#construct Child1\n";
        $this->addViewFile(__DIR__ . '/Child1.php');
    }

    function render()
    {
        echo "#render Child1\n";
        parent::render();
    }

}

class Child2 extends \aeqdev\ATPL
{

    function __construct()
    {
        echo "#construct Child2\n";
        $this->addViewFile(__DIR__ . '/Child2.php');
    }

    function render()
    {
        echo "#render Child2\n";
        parent::render();
    }

}
