<?php
/**
 * @author Petr Grishin <petr.grishin@grishini.ru>
 */

class OneSideRelationTest extends PHPUnit_Framework_TestCase {

    public function testGetStorage() {
        /** @var \PHPUnit_Framework_MockObject_MockObject|CActiveRecord $model */
        $model = $this
            ->getMockBuilder('CActiveRecord')
            ->disableOriginalConstructor()
            ->setMethods(array('__get', 'setAttribute'))
            ->getMock();

        $model
            ->expects($this->any())
            ->method('__get')
            ->will($this->returnCallback(function ($name) {
                return '[]';
            }))->with('attributes');

        $model
            ->expects($this->once())
            ->method('setAttribute')
            ->with('data', '[1,2,3]');

        $behavior = new \PetrGrishin\OneSideRelation\OneSideRelation;
        $behavior->setFieldNameStorage('data');
        $behavior->attach($model);
        $behavior->getStorage()->setArray(array(1, 2, 3));
        $this->assertEquals(array(1, 2, 3), $behavior->getStorage()->getArray());
    }

    public function testGetRelated() {
        /** @var \PHPUnit_Framework_MockObject_MockObject|CActiveRecord $model */
        $model = $this
            ->getMockBuilder('CActiveRecord')
            ->disableOriginalConstructor()
            ->setMethods(array('getAttribute'))
            ->getMock();

        $model
            ->expects($this->any())
            ->method('getAttribute')
            ->will($this->returnCallback(function ($name) {
                return '[1]';
            }))->with('data');

        $behavior = new \PetrGrishin\OneSideRelation\OneSideRelation;
        $behavior->setFieldNameStorage('data');
        $behavior->attach($model);
        $this->assertEquals(array(1), $behavior->getStorage()->getArray());
    }

}
