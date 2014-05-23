<?php
/**
 * @author Petr Grishin <petr.grishin@grishini.ru>
 */

namespace PetrGrishin\OneSideRelation;


use CActiveRecordBehavior as Behavior;
use PetrGrishin\ArrayField\ArrayFieldBehavior;
use SebastianBergmann\Exporter\Exception;

class OneSideRelation extends Behavior {
    const BEHAVIOR_NAME_STORAGE = 'arrayFieldStorage';

    /** @var  string */
    private $_relationModel;
    /** @var  string */
    private $_fieldNameStorage;
    /** @var \CActiveRecord[]  */
    private $_models = array();

    /**
     * @return string
     */
    public function getRelationModel() {
        return $this->_relationModel;
    }

    /**
     * @param string $className
     * @return $this
     */
    public function setRelationModel($className) {
        $this->_relationModel = $className;
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

    /**
     * @return ArrayFieldBehavior
     * TODO: make protected
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

    protected function getData() {
        return $this->getStorage()->getArray();
    }

    public function getRelated() {
        return array_filter(array_map(function ($pk) {
            return $this->getRelatedByPk($pk);
        }, $this->getData()));
    }

    protected function getRelatedByPk($pk) {
        if (!in_array($pk, $this->getData())) {
            throw new Exception(sprintf('Not found related with pk `%s`', $pk));
        }
        if (!isset($this->_models[$pk])) {
            $this->_models[$pk] = $this->getRelationFindModel()->findByPk($pk);
        }
        return $this->_models[$pk];
    }

    /**
     * @return \CActiveRecord
     */
    protected function getRelationFindModel() {
        /** @var $className \CActiveRecord */
        $className = $this->_relationModel;
        return $className::model();
    }
}
 