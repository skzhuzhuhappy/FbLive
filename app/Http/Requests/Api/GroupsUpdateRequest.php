<?php

namespace App\Http\Requests\Api;


class GroupsUpdateRequest extends FormRequest
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
                        //'name' => ['required', 'max:12'],
                        //'category_id' => ['required','integer'],
                        //'area_id' => ['required','integer'],
                        //'node' => ['required','Integer','between:1,3'],
                        //'summary' => ['required','string'],

                        'publish_permission' => ['Integer','between:1,3'],
                        'join_permission' => ['Integer','between:1,2'],
                        'feed_status' => ['Integer','between:0,1'],
                        'visible' => ['Integer','between:0,1'],
                        'status' => ['Integer','between:0,2'],
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
            'id.required'=>'用户ID必须填写',
            'id.exists'=>'用户不存在',
            'name.unique' => '圈子名称已经存在',
            'name.required' => '圈子名称不能为空',
            'category_id.required' => '类型不能为空',
            'category_id.integer' => '类型是数字类型',
            'area_id.required' => '地区不能为空',
            'area_id.integer' => '地区数字类型',
            'node.required' => 'node不能为空',
            'node.between' => 'node in 1，2，3',
            'summary.required' => '简介不能为空',
            'name.max' => '圈子名称最大长度为12个字符',
            'password.max' => '密码长度不能超过16个字符',
            'password.min' => '密码长度不能小于6个字符'
        ];
    }
}
