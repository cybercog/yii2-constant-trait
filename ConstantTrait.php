<?php

namespace chungachguk\traits;

/**
 * Трейт для получение списка констант
 */
trait ConstantTrait
{

    /**
     * @var \yii\di\Container
     */
    private $constantContainer;

    /**
     * Получить список констант текущего класса
     *
     * @param array $options список свойств класса ConstantHelper
     * @return ConstantHelper
     */
    public static function getConstants($options = [])
    {
        $options['class'] = ConstantHelper::className();
        $options['targetClass'] = __CLASS__;

        return \Yii::createObject($options)->extract();
    }

}
