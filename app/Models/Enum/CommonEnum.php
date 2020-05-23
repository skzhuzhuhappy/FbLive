<?php

namespace App\Models\Enum;
class CommonEnum
{
    // 状态类别
    const INVALID = 0; //已删除
    const NORMAL = 1; //正常
    const FREEZE = 2; //冻结

    public static function getStatusName($status){
        switch ($status){
            case self::INVALID:
                return '未审核';
            case self::NORMAL:
                return '正常';
            case self::FREEZE:
                return '审核未通过';
            default:
                return '正常';
        }
    }
}
