<?php
/**
 * @author Petr Grishin <petr.grishin@grishini.ru>
 */

namespace PetrGrishin\OneSideRelation;


use CActiveRecordBehavior as Behavior;

class OneSideRelation extends Behavior {
    const BEHAVIOR_STORAGE = 'arrayFieldStorage';

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

    protected function getStorage() {
        if (!$storage = $this->asa(self::BEHAVIOR_STORAGE)) {
            $storage = $this->attachBehavior(self::BEHAVIOR_STORAGE, array(
                'class' => \PetrGrishin\ArrayField\ArrayFieldBehavior::className(),
                'model' => $this->getOwner(),
                'fieldNameStorage' => $this->getFieldNameStorage()
            ));
        }
        return $storage;
    }
}
 