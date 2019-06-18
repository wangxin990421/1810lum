<?php

namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\UserModel;
use GuzzleHttp\Client;
class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }
    /**
     * 注册方法
     */
    public function reg(Request $request)
    {
        $data =$request->input();
//        dump($data);
        if($data['u_pwd'] != $data['u_pwd1']){  //判断两次输入密码是否一致
            echo "请确认两次输入密码一致！";die;
        }
        unset($data['u_pwd1']);   //去除多余字段
        $res = UserModel::insert($data);
//        dd($res);
        if($res){
            echo "注册成功！";
        }
    }

    /**
     * 登录方法
     */
    public function login(Request $request)
    {
        //接受数据
        $data = $request->input();
//        dd($data);
        $name = $data['u_name'];
//        dd($name);
        //查询数据库中用户是否存在
        $user = UserModel::where(['u_name'=>$name])->first();
//        dd($user);
        if(empty($user)){   //用户不存在
            echo "用户不存在，请先进行注册！谢谢";die;
        }else{       //用户存在
          if($data['u_pwd'] != $user->u_pwd){   //判断输入密码与数据库密码是否一致
              echo "密码输入错误，请重新输入！";
          }else{    //密码一致 进行登录
              echo "登录成功！";
          }
        }
    }

    /**
     * 修改密码
     */
    public function updatepwd(Request $request)
    {
        $data = $request->input();
//        dd($data);
        $name =  $data['u_name'];
//        dd($name);
        $user = UserModel::where(['u_name'=>$name])->first();
//        dd($user);
        if(empty($user)){   //用户不存在
            echo "您所修改密码的用户不存在！";die;
        }else{             //用户存在
            if($data['u_pwd'] != $user->u_pwd){    //判断原密码是否输入正确
                echo "原密码输入错误！";
            }else{                                //原密码输入正确后 进行修改
                $res =  UserModel::where(['u_name'=>$name])->update(['u_pwd'=>$data['u_pwd1']]);
//                dd($res);
                if($res){
                    echo "修改成功！";
                }
            }
        }
    }

    /**
     * 天气查询
     */
    public function weather(Request $request)
    {
        $count = $data = $request->input('weather');
//        dd($count);
        $tq1 = "天气";
        $city1 = '';
        if (substr($count, -6) == $tq1) {
            $tq = substr($count, -6);
            $city = substr($count, 0, -6);
            $city1 = $city . $tq;
        }
        if ($city1 == "天气") {
            $msg = "前边加个城市啊！不然我怎么知道是哪啊！不要为难人家了啊！";
        } else {
            $weather = file_get_contents("http://api.k780.com/?app=weather.future&weaid={$city}&appkey=42237&sign=3c5ca22475d913af875696a7f871b542&format=json");
            $weather = json_decode($weather, true);
            $msg = "";
            foreach ($weather['result'] as $k => $v) {
                $msg .= $v['days'] . "-" . $v['week'] . "-" . $v['citynm'] . "-" . $v['temperature'] . "\n";

            }

        }
        echo $msg;die;
    }

    /**
     * 图片上传
     */
    public function upload(Request $request)
    {
        print_r($_POST);
        print_r($_FILES);
        var_dump($_FILES);die;
         $data = $request()->input();
         dd($data); 
    }

    /**
     * 数据加密测试
     */
    public function  ceshi()
    {
        $data = file_get_contents('php://input');
//        dd($data);
        $enc_data = base64_decode($data);
//        dd( $enc_data);
        return $enc_data;
    }

    /**
     * 数据加密 对称加密
     */

    public function decypt1()
    {

        $method = "AES-128-CBC";  //加密算法
        $key = "password";         //加密秘钥
        $iv = "qwertyuiopasdfgh";  //初始化向量
        $data = base64_decode(file_get_contents('php://input'));  //接受数据
        echo ($data);echo "<hr>";
        $dec_data = openssl_decrypt($data,$method,$key,OPENSSL_RAW_DATA,$iv);  //解密数据
        echo $dec_data;

    }

    /**
     * 数据加密 非对称数据加密  公钥解密
     */
    public function decypt2()
    {
        $enc_data = file_get_contents('php://input');

        //公钥解密
        $pub_key = openssl_get_publickey("file://".storage_path("keys/pub.key"));
        //dd($pub_key);
        openssl_public_decrypt($enc_data,$dec_data,$pub_key);

        echo "解密数据：".$dec_data;
    }

    /**
     * 6.13 练习
     */
    public function lianxi()
    {
        $data=file_get_contents('php://input');
        $data=unserialize($data);
//        dd($data);
        $enc_data = $data['enc_datd'];
        $str1 = $data['signature'];
//        echo ($str1);die;

        //对称数据 解密
        $method = "AES-128-CBC";  //加密算法
        $key = "password";        //加密秘钥
        $iv = "asdfghjklzxcvbnm"; //初始化向量  16字节
        $dec_data = openssl_decrypt($enc_data,$method,$key,OPENSSL_RAW_DATA,$iv);  //解密数据
        echo "首次解密数据：".$dec_data;echo"<hr>";


        //获取公钥
        $pub_key = openssl_get_publickey("file://".storage_path("keys/pub.key"));
        //验证签名
        $ok = openssl_verify($enc_data, $str1, $pub_key);

        if ($ok == 1) {
            echo "首次验签成功";echo "<hr>";
            echo "<br>";
            //再次返回  数据再次对称加密
            $method = "AES-128-CBC";  //加密算法
            $key = "password";        //加密秘钥
            $iv = "zzzzzzzzzzzzzzzz"; //初始化向量  16字节

            //对称加密
            $enc_data = openssl_encrypt($dec_data,$method,$key,OPENSSL_RAW_DATA,$iv);  //加密数据
            echo"<hr>";
            echo "再次返回加密数据：".$enc_data;echo"<hr>";

            //生成私钥签名
            $private_key = openssl_get_privatekey("file://".storage_path("keys/priva2.pem"));
//        dd($private_key);
            openssl_sign($enc_data,$signature,$private_key);
            echo "再次私钥生成签名:".$signature;echo"<hr>";

            //返回数据
            $datainfo = [
                'enc_datd'=>$enc_data,
                'signature'=>$signature
            ];
            $dataInfo = serialize($datainfo);

            //发送数据
            $c = new Client();
            $url = "http://wangxin.1810api.com/test/lianxi2";    //访问路径
            $r = $c ->request('POST',$url,['body'=>$dataInfo]);

//            echo "<hr>";
            echo $r ->getBody();

        } elseif ($ok == 0) {
            echo "失败";echo"<hr>";
        } else {
            echo "内部错误";echo"<hr>";
        }
    }
}
