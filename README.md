yii-one-side-relation
=====================
[![Travis CI](https://travis-ci.org/petrgrishin/yii-one-side-relation.png "Travis CI")](https://travis-ci.org/petrgrishin/yii-one-side-relation)
[![Coverage Status](https://coveralls.io/repos/petrgrishin/yii-one-side-relation/badge.png?branch=master)](https://coveralls.io/r/petrgrishin/yii-one-side-relation?branch=master)

One side relation behavior

Installation
------------
Add a dependency to your project's composer.json:
```json
{
    "require": {
        "petrgrishin/yii-one-side-relation": "~1.0"
    }
}
```

Usage examples
--------------
#### Attach behavior to you model
Model have text attribute `data` for storage relational data

```php

use \CActiveRecord as ActiveRecord;
use \PetrGrishin\OneSideRelation\OneSideRelation;

class Model extends ActiveRecord {
    public function behaviors() {
        return array(
            'testRelation' => array(
                'class' => OneSideRelation::className(),
                'fieldNameStorage' => 'data',
                'relationModel' => RelationModel::className(),
            )
        );
    }

}
```

#### Usage behavior
```php
$model = Model::find(1)->one();
$relatedRecords = $model->testRelation->getRelated();
$model->testRelation->addRelated(new RelationModel());
$model->save();
```