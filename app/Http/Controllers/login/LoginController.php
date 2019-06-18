<?php

namespace App\Http\Controllers\login;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\AdminModel;
use GuzzleHttp\Client;
class LoginController extends Controller
{
     /**
      * 注册
      */

     public function reg(Request $request)
     {
         header('Access-Control-Allow-Origin:*');
         $data = $request->input();
//         return $data;die;
         $admin = $data['admin'];
//         dd($admin);
         $adminInfo = AdminModel::where(['a_name'=>$admin])->first();
//         dd($adminInfo);
         if($adminInfo){
             return $this->no("该用户已经注册过了，请更换用户！");die;
         }
//
         unset($data['pwd1']);
         $pwd = md5($data['pwd']);
         $dateInfo =[
             'a_name'=>$data['admin'],
             'a_pwd'=>$pwd,
             'a_email'=>$data['email']
         ];
         $res = AdminModel::insert($dateInfo);
//         dd($res);
         if($res == "true"){
             return $this->ok("您已经注册成功！请进行登录！");
         }else{
             return $this->no("注册失败，请检查数据是否正确！");
         }

     }

     /**
      * 登录
      */

     public function login(Request $request)
     {
         header('Access-Control-Allow-Origin:*');
         $data = $request->input();
//         return $data;
         $admin = $data['admin'];
         $admininfo = AdminModel::where(['a_name'=>$admin])->first();
         if(empty($admininfo)){
            return  $this->no("该用户还未注册，请先进行注册，在进行登录！");die;
         }else if(md5($data['pwd']) != $admininfo['a_pwd']){
            return  $this->no("密码输入错误，请确认密码，在进行登录！");die;
         }else{
             file_put_contents('login.txt',$admininfo);
//             echo 1;die;
             return $this->ok("祝贺您啊！登录成功！");

         }
     }

     /**
      * 查询用户等信息
      */

     public function admin()
     {
         header('Access-Control-Allow-Origin:*');
         $data = file_get_contents('login.txt');
//         $data = json_decode($data);
         return $data;
     }

    public function ok($font=''){
        $message=[
            'font'=>$font,
            'code'=>1
        ];
        return  json_encode($message);
    }

    public function no($font=''){
        $message=[
            'font'=>$font,
            'code'=>2
        ];
        return  json_encode($message);exit;
    }




}