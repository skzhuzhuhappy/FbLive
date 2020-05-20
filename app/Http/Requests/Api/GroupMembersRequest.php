<?php

namespace App\Http\Requests\Api;


class GroupMembersRequest extends FormRequest
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
                        'group_id' => ['required','exists:groups,id','integer'],
                        'user_id' =>  ['required','exists:users,id','integer'],
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
            'group_id.required' => '圈子不能为空',
            'group_id.integer' => '圈子是数字类型',
            'group_id.exists'=>'圈子不存在',

            'user_id.required' => '用户不能为空',
            'user_id.integer' => '用户是数字类型',
            'user_id.exists'=>'用户不存在',

        ];
    }
}
