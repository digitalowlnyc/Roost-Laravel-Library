<?php
/**
 * Creator: Bryan Mayor
 * Company: Blue Nest Digital, LLC
 * License: (All rights reserved)
 */

namespace Roost\LaravelTools\Laravel\Models;

class ModelHelpers {

    /**
     * Same as newModel() except the model is also saved
     * @param $modelClass
     * @param $attributes
     * @return mixed
     * @throws \Exception
     */

    static function createModel($modelClass, $attributes) {
        $model = self::newModel($modelClass, $attributes);
        if(!$model->save()) {
            throw new \Exception('Problem saving new model of type: ' . $modelClass);
        }

        return $model;
    }

    /**
     * Creates (and saves) a model with some added extras:
     * - Check if attributes are actually fillable
     * - Check if save() returns true and throw if not
     * @param $modelClass
     * @param $attributes
     * @return mixed
     * @throws
     * @throws \Exception
     */
    static function newModel($modelClass, $attributes) {
        $model = new $modelClass;

        $fillable = $model->getFillable();

        foreach($attributes as $attr=>$val) {
            /*
            if(!in_array($attr, $fillable)) {
                throw new \Exception('Error when creating now ' . $modelClass . ' model - Field is not fillable: ' . $attr);
            }
            */
            $model->{$attr} = $val;
        }

        return $model;
    }


    public static function safeAttributes($model)
    {
        $unsafe = ['id', 'password', 'token', 'secret', 'created_at', 'updated_at'];
        $attributes = $model->getAttributes();
        foreach($attributes as $k => $v) {
            if(in_array($k, $unsafe)) {
                unset($attributes[$k]);
            }
        }
        return $attributes;
    }

    public static function attr($model, $attribute, $default = '') {
        $attributes = explode('.', $attribute);

        $value = $model;
        foreach($attributes as $attribute) {
            if(isset($value->{$attribute})) {
                $value = $value->{$attribute};
            } else {
                return $default;
            }
        }

        return $value;
    }
}