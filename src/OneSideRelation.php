<?php
/**
 * @author Petr Grishin <petr.grishin@grishini.ru>
 */

namespace PetrGrishin\OneSideRelation;


use CActiveRecordBehavior as Behavior;

class OneSideRelation extends Behavior {
    /** @var  string */
    private $_className;
    /** @var  string */
    private $_fieldName;

    /**
     * @return string
     */
    public function getClassName() {
        return $this->_className;
    }

    /**
     * @param string $className
     * @return $this
     */
    public function setClassName($className) {
        $this->_className = $className;
        return $this;
    }

    /**
     * @return string
     */
    public function getFieldName() {
        return $this->_fieldName;
    }

    /**
     * @param string $fieldName
     * @return $this
     */
    public function setFieldName($fieldName) {
        $this->_fieldName = $fieldName;
        return $this;
    }

    public function attach($owner) {
        parent::attach($owner);
        $this->init();
    }

    protected function init() {

    }
}
 