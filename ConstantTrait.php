<?php

namespace chungachguk\traits;

/**
 * Трейт для получение списка констант
 */
trait ConstantTrait
{

    /**
     * Получить список констант текущего класса
     *
     * @param array $options список свойств класса ConstantHelper
     * @return ConstantHelper
     */
    public static function getConstants($options = [])
    {
        $options['class'] = '\chungachguk\traits\ConstantHelper';
        $options['targetClass'] = __CLASS__;

        return \Yii::createObject($options)->extract();
    }

}
