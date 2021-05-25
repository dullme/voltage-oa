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

/**
 * 下单进度
 */
function getSalesOrderProgress(array $vendors, array $vendor_ids){
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

    return $progress;
}
