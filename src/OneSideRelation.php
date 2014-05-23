<?php
/**
 * @author Petr Grishin <petr.grishin@grishini.ru>
 */

namespace PetrGrishin\OneSideRelation;


use CActiveRecordBehavior as Behavior;
use PetrGrishin\ArrayField\ArrayFieldBehavior;

class OneSideRelation extends Behavior {
    const BEHAVIOR_NAME_STORAGE = 'arrayFieldStorage';

    /** @var  string */
    private $_className;
    /** @var  string */
    private $_fieldNameStorage;

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
    public function getFieldNameStorage() {
        return $this->_fieldNameStorage;
    }

    /**
     * @param string $fieldName
     * @return $this
     */
    public function setFieldNameStorage($fieldName) {
        $this->_fieldNameStorage = $fieldName;
        return $this;
    }

    public function attach($owner) {
        parent::attach($owner);
        $this->init();
    }

    protected function init() {

    }

    /**
     * @return ArrayFieldBehavior
     */
    public function getStorage() {
        if (!$storage = $this->getOwner()->asa(self::BEHAVIOR_NAME_STORAGE)) {
            $storage = $this->getOwner()->attachBehavior(self::BEHAVIOR_NAME_STORAGE, array(
                'class' => ArrayFieldBehavior::className(),
                'fieldNameStorage' => $this->getFieldNameStorage()
            ));
        }
        return $storage;
    }
}
 