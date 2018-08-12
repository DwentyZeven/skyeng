<?php

class Math
{
    /**
     * Вычисляет сумму двух положительных чисел, отрицательные берет по модулю.
     *
     * @param string $num1
     * @param string $num2
     * @return string
     */
    public static function addAbs(string $num1, string $num2): string
    {
        $array1 = str_split(ltrim($num1, '-'));
        $array2 = str_split(ltrim($num2, '-'));

        $length1 = count($array1);
        $length2 = count($array2);
        $maxLength = $length1 > $length2 ? $length1 : $length2;

        $total = '';
        $residue = 0;

        for ($i = 1; $i <= $maxLength; $i++) {
            $value = (int) array_pop($array1) + (int) array_pop($array2) + $residue;
            $total = ($value > 9 ? $value - 10 : $value) . $total;
            $residue = $value > 9 ? 1 : 0;
        }

        return ($residue == 0) ? $total : $residue . $total;
    }

    /**
     * Вычисляет сумму двух любых чисел, одно из которых или оба могут быть отрицательными.
     *
     * @param string $num1
     * @param string $num2
     * @return string
     */
    public static function add(string $num1, string $num2): string
    {
        $array1 = str_split($num1);
        $array2 = str_split($num2);

        // Определяем какие из чисел отрицательные и убираем знак '-' у них
        if ($isNegNum1 = current($array1) == '-') {
            array_shift($array1);
        }
        if ($isNegNum2 = current($array2) == '-') {
            array_shift($array2);
        }

        $length1 = count($array1);
        $length2 = count($array2);
        $maxLength = $length1 > $length2 ? $length1 : $length2;
        $total = '';

        if ((!$isNegNum1 || !$isNegNum2) && ($isNegNum1 || $isNegNum2)) {
            // Вычисляем разность, если одно число отрицательное, а одно - положительное
            if (null === $maxAbs = self::maxAbs($num1, $num2)) {
                // Если числа равны по модулю, то их разность равна нулю
                return '0';
            }
            $residue = 0;
            for ($i = 1; $i <= $maxLength; $i++) {
                // Вычитаем из значений того массива, исходное число которого больше по модулю
                $value = ($maxAbs == $num1)
                    ? (int) array_pop($array1) - (int) array_pop($array2)
                    : (int) array_pop($array2) - (int) array_pop($array1);
                $value -= $residue;
                $total = ($value < 0 ? 10 + $value : $value) . $total;
                $residue = $value < 0 ? 1 : 0;
            }
            $total = ltrim($total, '0');
            $total = ($isNegNum1 && $maxAbs == $num1) || ($isNegNum2 && $maxAbs == $num2) ? '-' . $total : $total;
        } else {
            // Вычисляем сумму, если оба числа отрицательные или положительные
            $residue = 0;
            for ($i = 1; $i <= $maxLength; $i++) {
                $value = (int) array_pop($array1) + (int) array_pop($array2) + $residue;
                $total = ($value > 9 ? $value - 10 : $value) . $total;
                $residue = $value > 9 ? 1 : 0;
            }
            $total = $residue == 0 ? $total : $residue . $total;
            $total = !$isNegNum1 ? $total : '-' . $total;
        }

        return $total;
    }

    /**
     * Из двух переданных чисел возвращает то, которое больше по модулю.
     * Если числа равны по модулю, то возвращается null.
     *
     * @param string $num1
     * @param string $num2
     * @return string|null
     */
    public static function maxAbs($num1, $num2): ?string
    {
        $array1 = str_split(ltrim($num1, '-'));
        $array2 = str_split(ltrim($num2, '-'));

        $length1 = count($array1);
        $length2 = count($array2);

        if ($length1 != $length2) {
            return ($length1 > $length2) ? $num1 : $num2;
        }

        for ($i = 0; $i < $length1; $i++) {
            if ($array1[$i] != $array2[$i]) {
                return ($array1[$i] > $array2[$i]) ? $num1 : $num2;
            }
        }

        return null;
    }
}


/**
 * Использование функций
 */
$num1 = PHP_INT_MAX . 1;
$num2 = PHP_INT_MAX . 1;

$result = Math::add($num1, $num2);
var_dump($result);

$result = Math::addAbs($num1, $num2);
var_dump($result);

$result = bcadd($num1, $num2);
var_dump($result);