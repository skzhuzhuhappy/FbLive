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
                return '待审核';
            case self::NORMAL:
                return '通过审核';
            case self::FREEZE:
                return '未通过审核';
            default:
                return '正常';
        }
    }
}
