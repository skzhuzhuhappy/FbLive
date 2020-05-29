<?php

namespace App\Http\Requests\Api;



class FeedLikeRequest extends FormRequest
{
    public function rules()
    {

        switch ($this->method()) {
            case 'GET':
                {
                    return [
                        //'id' => ['required,exists:users,id']
                    ];
                }
            case 'POST':
                {
                    return [
                        //'from_id' => ['required',['between'=>1,3],'integer'],
                        'user_id' => ['required','exists:users,id','integer'],
                        'feed_id' => ['required','exists:feeds,id','integer'],
                    ];
                }
            case 'PUT':
            case 'PATCH':
            case 'DELETE':
            default:
                {
                    return [

                    ];
                }
        }
    }

    public function messages()
    {
        return [
            'from_id.required' => '来源不能为空',
            'from_id.integer' => '来源是数字类型',
            'user_id.required' => '用户ID不能为空',
            'user_id.integer' => '用户ID是数字类型',
            'user_id.exists'=>'用户不存在',
            'feed_id.required' => '动态ID不能为空',
            'feed_id.integer' => '动态ID是数字类型',
            'feed_id.exists'=>'动态不存在',
        ];
    }
}
