<?php

namespace App\Http\Requests\Api;



class CategoryRequest extends FormRequest
{
    public function rules()
    {

        switch ($this->method()) {
            case 'GET':
                {
                    return [
                        'id' => ['required,exists:group_categories,id','integer']
                    ];
                }
            case 'POST':
                {
                   /* return [
                        'id' => ['required','exists:users,id','integer'],
                    ];*/
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
            'id.required' => '圈子类型ID不能为空',
            'id.integer' => '圈子类型ID是数字类型',
            'id.exists'=>'圈子类型不存在',
        ];
    }
}
