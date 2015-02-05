<?php

namespace chungachguk\traits;

use yii\base\UnknownPropertyException;

/**
 * Класс получения списка констант класса с использованием механизма Reflection
 *
 * @package chungachguk\traits
 */
class ConstantHelper
{
    /**
     * @var array массив констант заданного класса
     */
    private static $constants = [];

    /**
     * @var string|Object класс или объект, у которого необходимо извлечь список констант
     */
    public $targetClass;

    /**
     * @var string шаблон формирования ключа массива констант
     */
    public $keyPattern = '{key}';

    /**
     * @var string шаблон формирования значения массива констант
     */
    public $valuePattern = '{value}';

    /**
     * @var string префикс имени константы
     */
    public $prefix = '';

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

        if (is_object($this->targetClass)) {
            $this->targetClass = get_class($this->targetClass);
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