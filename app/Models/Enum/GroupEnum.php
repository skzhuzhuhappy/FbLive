<?php

namespace App\Models\Enum;
class GroupEnum
{
    // 状态类别 1: 公开，2：私有，3：付费的
    const INVALID = 1; //公开
    const NORMAL = 2; //私有
    const FREEZE = 3; //付费的

    public static function getStatusName($status){
        switch ($status){
            case self::INVALID:
                return '公开';
            case self::NORMAL:
                return '私有';
            case self::FREEZE:
                return '付费的';
            default:
                return '公开';
        }
    }
}
