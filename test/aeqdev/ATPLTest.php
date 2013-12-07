<?php

namespace aeqdev\ATPLTest;

require_once __DIR__ . '/../../aeqdev/ATPL.php';
require_once __DIR__ . '/ATPLTest/Test.php';

class ATPLTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var ATPL
     */
    protected $object;

    protected function setUp()
    {
    }

    protected function tearDown()
    {
    }

    public function testFull()
    {
        ob_start();

        $layout1 = new Layout1Inside();
        $layout2 = new Layout2();
        $content = new Content();
        $child1 = new Child1();
        $child2 = new Child2();

        echo "\n";

        $content->wrap($layout2);
        $content->wrap($layout1);
        $content->push($child1);
        $content->push($child2);


        $content->render();

        echo "\n";

        $content->render();

        $result = ob_get_clean();

        $this->assertEquals(
'#construct Layout1Inside
#construct Layout1
#construct Layout1Base
#construct Layout2
#construct Content
#construct ContentBase
#construct Child1
#construct Child2

#render Content
#render ContentBase
#render Layout1Inside
#render Layout1
#render Layout1Base
Layout1 begin (extended around Layout1Base)
    Layout1Base begin
        Layout1Inside begin (extended filling Layout1)
#render Layout2
            Layout2 begin (wrapped around $content before than $layout1)
                Content begin (extended ContentBase)
                    ContentBase begin
#render Child1
                        Child1 contents
#render Child2
                        Child2 contents
                    ContentBase end
                Content end
            Layout2 end
        Layout1Inside end
    Layout1Base end
Layout1 end

#render Content
#render ContentBase
#render Layout1Inside
#render Layout1
#render Layout1Base
Layout1 begin (extended around Layout1Base)
    Layout1Base begin
        Layout1Inside begin (extended filling Layout1)
#render Layout2
            Layout2 begin (wrapped around $content before than $layout1)
                Content begin (extended ContentBase)
                    ContentBase begin
#render Child1
                        Child1 contents
#render Child2
                        Child2 contents
                    ContentBase end
                Content end
            Layout2 end
        Layout1Inside end
    Layout1Base end
Layout1 end
',
            $result);
    }

}
