<?php
/**
 * @author Petr Grishin <petr.grishin@grishini.ru>
 */

namespace PetrGrishin\OneSideRelation;


use CActiveRecord as ActiveRecord;
use CActiveRecordBehavior as Behavior;
use PetrGrishin\ArrayField\ArrayFieldBehavior;
use SebastianBergmann\Exporter\Exception;

class OneSideRelation extends Behavior {
    const BEHAVIOR_NAME_STORAGE = 'arrayFieldStorage';

    /** @var  string */
    private $_relationModel;
    /** @var  string */
    private $_fieldNameStorage;
    /** @var ActiveRecord[]  */
    private $_models = array();
    /** @var ActiveRecord */
    private $_relationFindModel;

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

    protected function setData(array $data) {
        $this->getStorage()->setArray($data);
        return $this;
    }

    public function getRelated() {
        return array_filter(array_map(function ($pk) {
            return $this->getRelatedByPk($pk);
        }, $this->getData()));
    }

    public function addRelated(ActiveRecord $model) {
        $this->_models[$model->id] = $model;
        $this->saveData();
        return $this;
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
     * @return ActiveRecord
     */
    public function getRelationFindModel() {
        if (empty($this->_relationFindModel)) {
            $this->_relationFindModel = ActiveRecord::model($this->_relationModel);
        }
        return $this->_relationFindModel;
    }

    public function setRelationFindModel($model) {
        $this->_relationFindModel = $model;
        return $this;
    }

    protected function saveData() {
        $this->setData(array_keys($this->_models));
        return $this;
    }

    protected function beforeSave() {
        $this->saveData();
    }
}
 