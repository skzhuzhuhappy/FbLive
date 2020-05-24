<?php

namespace App\Http\Requests\Api;



class FeedsRequest extends FormRequest
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
                        'from_id' => ['required','integer'],
                        'user_id' => ['required','exists:users,id','integer'],
                        'group_id' => ['required','exists:groups,id','integer'],
                        'text_body' =>  ['required','string'],
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
            'group_id.required' => '圈子ID不能为空',
            'group_id.integer' => '圈子ID是数字类型',
            'group_id.exists'=>'圈子ID不存在',
            'text_body.required' => '纯文字不能为空',
            'text_body.string' => '纯文字是字符串',
        ];
    }
}
