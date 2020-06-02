<?php

namespace App\Http\Controllers\Api;


use App\Transformers\PostTransformer;


class BaseController extends Controller
{

    function fb_login($name,$pwd) {

        $data['username'] =$name;
        $pwd = $this->http_request("http://media.fblife.com/encode/password", ['pwd' => $pwd]);
        var_dump($pwd);exit();

        $login_url = 'http://gw.fblife.com/bbs/api/user/login';//用户登录接口
        $data['password'] = $pwd;
        $data['reqTime'] = time();//此参数必传
        $sign = $this->createToken($data);
        $data['sign'] = $sign;
        $res=$this->post_json_ssl($login_url, $data);
        return $res;
    }

    #生成 token
    public function createToken($allParam) {
        if(isset($allParam['systemParameterInfo']['reqTime'])) {
            $reqTime = $allParam['systemParameterInfo']['reqTime'];
        }else{
            $reqTime =$allParam['reqTime'];
        }
        $newArr=array();
        ksort($allParam);
        asort($allParam,2);

        foreach ($allParam as $key => $val) {

            if (is_array($val)) {

                //修改如果数组里没有给KEY值的情况,如果这种情况把整个数组当做一个字符来处理
                if(isset($val[0])){

                    $allParam[$key] ="";//json_encode(json_encode($val));
                    //$allParam[$key] = substr( $allParam[$key], 1, strlen($allParam[$key])-2 );
                }else {
                    $allParam[$key] = $this->getArraySortString($val);
                }

                if (is_array($allParam[$key])) {


                    $allParam[$key] = $this->getArraySortString($allParam[$key], 2);

                }
            }else{
                $allParam[$key] = strval($val);
            }
        }



        asort($allParam,2);
        $sortKeys=$this->bubble_sort_keys($allParam);

        foreach ($sortKeys as $key => $value) {
            $newArr[$value]=$allParam[$value];
        }
        $allParam=$newArr;

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

        $keyIndex=$this->privateKeyIndex[gmdate('w',substr($reqTime,0,10))];
        //echo $sign_str . "&private_key=" . $this->priviteKey[$keyIndex];exit;
        return md5($sign_str . "&private_key=" . $this->priviteKey[$keyIndex]);
    }



    function post_json_ssl($url='',$arr=null){
        if (empty($url) || empty($arr)) {
            return false;
        }
        $data_string = json_encode($arr);
        $ch=curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); // 从证书中检查SSL加密算法是否存在
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$data_string);
        curl_setopt($ch,CURLOPT_HTTPHEADER,array(
                'Content-Type:application/json;charset=utf-8',
                'Content-Length:'.strlen($data_string)
            )
        );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result=curl_exec($ch);
        curl_close($ch);
        return $result;
    }



}
