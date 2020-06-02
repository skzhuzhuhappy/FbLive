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

    function getBbsUser($pwd, $name, $ip, $type = 1, $post_rul = 'https://fb-cms.fblife.com/api/web/user/login', $pwd_url = 'http://media.fblife.com/encode/password')
    {

        $post_data['password'] = $this->http_request($pwd_url, ['pwd' => $pwd]);
        //调用登录
        if ($type == 1) {
            $post_data['username'] = $name;
            $post_data['ip'] = $ip;
        }
        //$post_data['username'] = $name;
        //$post_data['ip'] = $ip;
        $post_data['name'] = $name;

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
                if ($datas['resInfo']['rspCode'] == 1000) {
                    $res = array();
                    $res['name'] = $datas['rspData']['username'];
                    $request = Request::instance();
                    $data['username'] = $request->post('name', '');;
                    $data['password'] = $request->post('pwd', '');
                    $login_url = 'http://gw.fblife.com/bbs/api/user/login';//用户登录接口
                    $data['password'] = $this->encodePassword($data['password']);//加密用户密码
                    $data['reqTime'] = time();//此参数必传
                    $sign = $this->createToken($data);
                    $data['sign'] = $sign;
                    $res = $this->post_json_ssl($login_url, $data);
                    return $res;

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


    function fb_login($name, $pwd)
    {

        $data['username'] = $name;
        $pwd = $this->encodePassword($pwd);
        var_dump($pwd);
        exit();

        $login_url = 'http://gw.fblife.com/bbs/api/user/login';//用户登录接口
        $data['password'] = $pwd;
        $data['reqTime'] = time();//此参数必传
        $sign = $this->createToken($data);
        $data['sign'] = $sign;
        $res = $this->post_json_ssl($login_url, $data);
        return $res;
    }

    #生成 token
    public function createToken($allParam)
    {
        if (isset($allParam['systemParameterInfo']['reqTime'])) {
            $reqTime = $allParam['systemParameterInfo']['reqTime'];
        } else {
            $reqTime = $allParam['reqTime'];
        }
        $newArr = array();
        ksort($allParam);
        asort($allParam, 2);

        foreach ($allParam as $key => $val) {

            if (is_array($val)) {

                //修改如果数组里没有给KEY值的情况,如果这种情况把整个数组当做一个字符来处理
                if (isset($val[0])) {

                    $allParam[$key] = "";//json_encode(json_encode($val));
                    //$allParam[$key] = substr( $allParam[$key], 1, strlen($allParam[$key])-2 );
                } else {
                    $allParam[$key] = $this->getArraySortString($val);
                }

                if (is_array($allParam[$key])) {


                    $allParam[$key] = $this->getArraySortString($allParam[$key], 2);

                }
            } else {
                $allParam[$key] = strval($val);
            }
        }


        asort($allParam, 2);
        $sortKeys = $this->bubble_sort_keys($allParam);

        foreach ($sortKeys as $key => $value) {
            $newArr[$value] = $allParam[$value];
        }
        $allParam = $newArr;

        $sign_str = '';
        $arr_str = "";
        foreach ($allParam as $key => $val) {

            if ($key != 'sign') {

                $sign_str .= $key . '=' . $val . "&";
            }
        }

        if (substr($sign_str, -1) == "&") {

            $sign_str = substr($sign_str, 0, strlen($sign_str) - 1);
        }

        $keyIndex = $this->privateKeyIndex[gmdate('w', substr($reqTime, 0, 10))];
        //echo $sign_str . "&private_key=" . $this->priviteKey[$keyIndex];exit;
        return md5($sign_str . "&private_key=" . $this->priviteKey[$keyIndex]);
    }


    function post_json_ssl($url = '', $arr = null)
    {
        if (empty($url) || empty($arr)) {
            return false;
        }
        $data_string = json_encode($arr);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); // 从证书中检查SSL加密算法是否存在
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type:application/json;charset=utf-8',
                'Content-Length:' . strlen($data_string)
            )
        );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    //密码加密
    function encodePassword($input, $key="com.fblife.app") {
        $key=md5($key);
        $key=substr($key, 0,16);
        $size = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_ECB);
        $pad = $size - (strlen($input) % $size);
        $input = $input . str_repeat(chr($pad), $pad);
        $td = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_ECB, '');
        $iv = mcrypt_create_iv (mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
        mcrypt_generic_init($td, $key, $iv);
        $data = mcrypt_generic($td, $input);
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);
        $data = base64_encode($data);
        return $data;
    }


}