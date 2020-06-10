<?php

namespace App\Http\Controllers\Api;

use App\Api\Helpers\ApiResponse;
use App\Http\Controllers\Controller as BaseController;
use Illuminate\Support\Facades\Storage;


class Controller extends BaseController
{

    use ApiResponse;

    // 其他通用的Api帮助函数


    function send_post($url, $content, $header = [])
    {
        $ch = curl_init();
        if (substr($url, 0, 5) == 'https') {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);  // 从证书中检查SSL加密算法是否存在
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        if (count($header) > 0) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        }
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($content));
        $response = curl_exec($ch);
        if ($error = curl_error($ch)) {
            die($error);
        }
        curl_close($ch);
        $response = json_decode($response, true);
        return $response;
    }

    function http_request($url, $data = null)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        if (!empty($data)) {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        $output = curl_exec($curl);
        curl_close($curl);
        return $output;
    }

    public function getBbsUser($name, $pwd,$type, $post_rul = 'https://fb-cms.fblife.com/api/web/user/login', $pwd_url = 'http://media.fblife.com/encode/password')
    {
        $post_data['name'] = $name;
        $post_data['pwd'] = $pwd;
        //调用登录
        if ($type == 1) {
            $post_data['password'] = $this->http_request($pwd_url, ['pwd' => $pwd]);
            $post_data['username'] = $name;
        }

        $datas = $this->send_post($post_rul, $post_data);

        switch ($type) {
            case 1:
                if ($datas['recode'] == 200) {
                    $res = array();
                    $res['name'] = $datas['body']['info']['username'];
                    if (!empty($datas['body']['info']['mobile']) && $datas['body']['info']['mobile'] != 'null') {
                        $res['phone'] = $datas['body']['info']['mobile'];
                    }
                    $res['avatar'] = $datas['body']['info']['icon'];
                    $res['forum_user_id'] = $datas['body']['info']['uid'];
                    $res['sex'] = $datas['body']['info']['type'];
                    $res['token'] = $datas['body']['info']['token'];

                    return $res;
                }
                break;
            case 2:
                //var_dump($datas);
                //exit();
                if ($datas['rspInfo']['rspCode'] == 1000) {
                    //var_dump($datas['rspInfo']['rspCode']);
                    //exit();
                    $res = array();
                    $res['name'] = $datas['rspData']['username'];
                    if (!empty($datas['rspData']['mobile']) && $datas['rspData']['mobile'] != 'null') {
                        $res['phone'] = $datas['rspData']['mobile'];
                    }
                    $res['avatar'] = $datas['rspData']['middlePortrait'];
                    $res['forum_user_id'] = $datas['rspData']['uid'];
                    $res['sex'] = 1;
                    $res['token'] = $datas['rspData']['token'];
                    return $res;
                }
                break;
            default;
        }

    }


    public static function save_base64($base64_img)
    {
        //保存图片
        preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_img, $res);

        $new_file = '' . date('Ym', time()) . '/';
        //获取图片类型
        $type = $res[2];
        $new_file = $new_file . str_random(10) . mt_rand(10000, 99999) . '.' . $type;
        $base64_img = base64_decode(str_replace($res[1], '', $base64_img));
        //存储图片

        $is_true = Storage::disk('group')->put($new_file, $base64_img);
        //存储图片
        if ($is_true) {
            $new_file = config('filesystems.disks.group.url') . $new_file;
            return $new_file;
        }

    }


    // 返回错误的请求
    protected function errorBadRequest($validator)
    {
        $result = [];
        $messages = $validator->errors()->toArray();

        if ($messages) {
            foreach ($messages as $field => $errors) {
                foreach ($errors as $error) {
                    $result[] = [
                        'field' => $field,
                        'code' => $error,
                    ];
                }
            }
        }
        return $result;
    }


}