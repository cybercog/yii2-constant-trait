<?php

namespace chungachguk\traits;

use yii\base\UnknownPropertyException;

/**
 * Класс получения списка констант класса с использованием механизма Reflection
 *
 * @package chungachguk\traits
 */
class ConstantHelper extends \yii\base\Object
{
    /**
     * @var array массив констант заданного класса
     */
    private static $constants = [];

    /**
     * @var string|\yii\base\Object класс или объект, у которого необходимо извлечь список констант
     */
    private $targetClass;

    /**
     * @var string шаблон формирования ключа массива констант
     */
    private $keyPattern = '{key}';

    /**
     * @var string шаблон формирования значения массива констант
     */
    private $valuePattern = '{value}';

    /**
     * @var string префикс имени константы
     */
    private $prefix = '';

    /**
     * @param string $value
     */
    public function setPrefix($value)
    {
        $this->prefix = $value;
    }

    /**
     * @param string $value
     */
    public function setKeyPattern($value)
    {
        $this->keyPattern = $value;
    }

    /**
     * @param string $value
     */
    public function setValuePattern($value)
    {
        $this->valuePattern = $value;
    }

    /**
     * @param string $value
     */
    public function setTargetClass($value)
    {
        $this->targetClass = $value;
    }

    /**
     * Извлечь массив (ключ => значение) констант
     *
     * @return array
     * @throws UnknownPropertyException
     */
    public function extract()
    {
        $result = [];

        if (empty($this->targetClass)) {
            throw new UnknownPropertyException('targetClass option is not defined');
        }

        if ($this->targetClass instanceof \yii\base\Object) {
            $this->targetClass = $this->targetClass->className();
        }

        if (!isset(self::$constants[$this->targetClass])) {
            self::$constants[$this->targetClass] = (new \ReflectionClass($this->targetClass))->getConstants();
        }

        foreach(self::$constants[$this->targetClass] as $constKey => $constValue) {
            if (!empty($this->prefix)) {
                if (false === strpos($constKey, $this->prefix, 0)) {
                    continue;
                }
            }

            $key = $this->processPattern($this->keyPattern, $constKey, $constValue);
            $value = $this->processPattern($this->valuePattern, $constKey, $constValue);

            $result += [
                $key => $value
            ];
        }

        return $result;
    }

    /**
     * @param string|\Closure $pattern
     * @param string|int $key
     * @param string|int $value
     * @return string
     */
    private function processPattern($pattern, $key, $value)
    {
        if ($pattern instanceof \Closure) {
            $pattern = call_user_func($pattern, $key, $value);
        }

        return strtr($pattern, [
            '{key}' => $key,
            '{value}' => $value
        ]);
    }

}