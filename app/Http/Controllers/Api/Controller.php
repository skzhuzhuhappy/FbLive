<?php

namespace App\Http\Controllers\Api;

use App\Api\Helpers\ApiResponse;
use App\Http\Controllers\Controller as BaseController;


class Controller extends BaseController
{

    use ApiResponse;

    // 其他通用的Api帮助函数

    //密码加密
    function encodePassword($input, $key = "com.fblife.app")
    {
        $key = md5($key);
        $key = substr($key, 0, 16);
        $size = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_ECB);
        $pad = $size - (strlen($input) % $size);
        $input = $input . str_repeat(chr($pad), $pad);
        $td = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_ECB, '');
        $iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
        mcrypt_generic_init($td, $key, $iv);
        $data = mcrypt_generic($td, $input);
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);
        $data = base64_encode($data);
        return $data;
    }


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
        if (!empty($data)){
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        $output = curl_exec($curl);
        curl_close($curl);
        return $output;
    }

    function getBbsUser($pwd,$name,$ip,$type=1,$post_rul='https://fb-cms.fblife.com/api/web/user/login',$pwd_url='http://media.fblife.com/encode/password'){

        $post_data['password']  = $this->http_request($pwd_url, ['pwd'=>$pwd]);
        //调用登录
        if($type == 1){
            $post_data['username'] = $name;
            $post_data['ip'] = $ip;
        }
        //$post_data['username'] = $name;
        //$post_data['ip'] = $ip;
        $post_data['name'] = $name;
        var_dump($post_data);
        $datas = $this->send_post($post_rul, $post_data);
        var_dump($datas);exit();

        switch ($type){
            case 1:
                if ($datas['recode'] == 200) {
                    $res = array();
                    $res['name'] = $datas['body']['info']['username'];
                    if(!empty($datas['body']['info']['mobile']) && $datas['body']['info']['mobile'] != 'null'){
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
                if ($datas['resInfo']['rspCode'] == 1000) {
                    $res = array();
                    $res['name'] = $datas['rspData']['username'];
                    if(!empty($datas['rspData']['mobile']) && $datas['rspData']['mobile'] != 'null'){
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


}