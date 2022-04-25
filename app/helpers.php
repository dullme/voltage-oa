<?php

function int2Excel($num)
{
    $az = 26;
    $m = (int)($num % $az);

    $q = (int)($num / $az);

    $letter = chr(ord('A') + $m);

    if ($q > 0) {
        return int2Excel($q - 1) . $letter;
    }
    return $letter;
}

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

/**
 * 下单进度
 */
function getOrderProgress(array $vendors, array $vendor_ids){
    if(count($vendors) == 0){
        $progress = 0;//未设置供应商进度为0
    }else{
        $progress = 0.5;//设置了供应商清单，下单进度为50%
        $in_array = 0;
        foreach ($vendors as $vendor){
            $in_array = in_array($vendor, $vendor_ids) ? ++$in_array : $in_array;
        }
        if($in_array == count($vendors)){ //如果设置的采购计划供应商数量和实际下单的数量相等 则进度为 100%
            $progress = 1;
        }else{
            $progress += 0.5/count($vendors) * $in_array;
        }
    }

    return intval(bigNumber($progress)->multiply(100)->getValue());
}

/**
 * 发货进度
 */
function getDeliveryProgress($shipped, $total_amount){

    return is_null($total_amount) ? 0 : intval(bigNumber($shipped)->divide($total_amount)->multiply(100)->getValue());
}

/**
 * 收款进度
 * @param $received
 * @param $total_amount
 * @return float|int
 */
function getReceivedProgress($received, $total_amount){
    return is_null($total_amount) ? 0 : intval(bigNumber($received)->divide($total_amount)->multiply(100)->getValue());
}
