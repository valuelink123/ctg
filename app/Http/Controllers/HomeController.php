<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use DB;

class HomeController extends Controller
{
    public function __construct()
    {

    }
    //首页展示
    public function index(Request $request)
    {
		return view('home');
    }
    //隐私申明展示
    public function privacyStatement()
    {
        $args['title'] = 'PRIVACY STATEMENT-Claim the gift';
    	return view('privacy_statement',['args'=>$args]);
    }
    /*
    * 文章内容
    * dbpower_projector
    * tens_pads
    * nursal_waist_L
    * nursal_waist_M
    * the_joy_of_food
    * 101_beauty_tips
    * seven_tips_friendsgiving
    * weight_loss
    * health_benefits
    * nutritional_supplements
     */
    public function article(Request $request)
    {
    	$type = isset($_REQUEST['type']) ? $_REQUEST['type'] : '';
    	return view('article.'.$type);
    }
    /*
    * blog页面
     */
    public function blog()
    {
        $args['title'] = 'BLOG-Claim the gift';
		return view('blog',['args'=>$args]);
    }
    /*
     * 测试客户自己输入信息
     */
    public function addTester()
    {
        $return['status'] = 1;
        $data['name'] = isset($_REQUEST['name']) ? $_REQUEST['name'] : '';
        $data['email'] = isset($_REQUEST['email']) ? $_REQUEST['email'] : '';
        $data['created_at'] = date('Y-m-d H:i:s');
        if($data['name'] && $data['email']){
            $res = Db::table('ctg_testers')->insert($data);
            if(!$res){
                $return['status'] = 0;
                $return['msg'] = 'System error';
            }
        }else{
            $return['status'] = 0;
            $return['msg'] ='Please enter your name or email address';
        }
        return $return;

    }

}
