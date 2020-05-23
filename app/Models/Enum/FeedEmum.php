<?php

namespace App\Models\Enum;
class FeedEmum
{
    // 状态类别
    const INVALID = 0; //待审核
    const NORMAL = 1; //
    const FREEZE = 1; //冻结

    public static function getStatusName($status){
        switch ($status){
            case self::INVALID:
                return '已删除';
            case self::NORMAL:
                return '正常';
            case self::FREEZE:
                return '冻结';
            default:
                return '正常';
        }
    }
}
