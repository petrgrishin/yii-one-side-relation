<?php
/**
 * @author Petr Grishin <petr.grishin@grishini.ru>
 */

class OneSideRelationTest extends PHPUnit_Framework_TestCase {

    public function test() {

        /** @var \PHPUnit_Framework_MockObject_MockObject|ActiveRecord $model */
        $model = $this
            ->getMockBuilder('CActiveRecord')
            ->disableOriginalConstructor()
            ->setMethods(array('__get', 'setAttribute'))
            ->getMock();

        $model
            ->expects($this->any())
            ->method('__get')
            ->will($this->returnCallback(function ($name) {
                return '{}';
            }))->with('attributes');

        $model
            ->expects($this->once())
            ->method('setAttribute')
            ->with('data', '{"a":{"b":true}}');

        $behavior = new \PetrGrishin\OneSideRelation\OneSideRelation;
        $behavior->setFieldNameStorage('data');
        $behavior->attach($model);
        $behavior->getStorage()->setArray(array('a' => array('b' => true)));
        $this->assertEquals(array('a' => array('b' => true)), $behavior->getStorage()->getArray());

    }
}
