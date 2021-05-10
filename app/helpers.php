<?php

/**
 * 默认的精度为小数点后两位
 * @param $number
 * @param int $scale
 * @return \Moontoast\Math\BigNumber
 */
function bigNumber($number, $scale = 2): \Moontoast\Math\BigNumber
{
    return new \Moontoast\Math\BigNumber($number, $scale);
}
