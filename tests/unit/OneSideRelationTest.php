<?php
/**
 * @author Petr Grishin <petr.grishin@grishini.ru>
 */

class TestRelationModel extends CActiveRecord {
}

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
                return '[1, 2]';
            }))->with('data');

        /** @var \PHPUnit_Framework_MockObject_MockObject|TestRelationModel $testRelationModel */
        $testRelationModel = $this
            ->getMockBuilder('TestRelationModel')
            ->disableOriginalConstructor()
            ->setMethods(array('findByPk'))
            ->getMock();
        $testRelationModel
            ->expects($this->any())
            ->method('findByPk')
            ->will($this->returnCallback(function ($pk) {
                return $pk;
            }));

        $behavior = new \PetrGrishin\OneSideRelation\OneSideRelation;
        $behavior->setFieldNameStorage('data');
        $behavior->attach($model);
        $behavior->setRelationModel('TestRelationModel');
        $this->assertInstanceOf('TestRelationModel', $behavior->getRelationFindModel());
        $behavior->setRelationFindModel($testRelationModel);
        $this->assertEquals(array(1, 2), $behavior->getStorage()->getArray());
        $this->assertEquals(array(1, 2), $behavior->getRelated());
    }

}
