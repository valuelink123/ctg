<?php

namespace App\Http\Controllers;
use DB;
use App\User;

class ApiController extends Controller
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 *
	 */

	/**
	 * Show the application dashboard.
	 *
	 * @return \Illuminate\Http\Response
	 */

	/*
	 * 得到验证码方法
	 * 从服务器端拉取验证码
	 */
	public function getCode()
	{
        header('Access-Control-Allow-Origin:*');

		session_start();//开启session记录验证码数据
		$userId = isset($_REQUEST['userId']) && $_REQUEST['userId'] ? $_REQUEST['userId'] : 0;
		$sessionId = isset($_REQUEST['sessionId']) && $_REQUEST['sessionId'] ? $_REQUEST['sessionId'] : 0;
        $getCodeType = isset($_REQUEST['getCodeType']) && $_REQUEST['getCodeType'] ? $_REQUEST['getCodeType'] : 0;
		session_id($sessionId);//启用传过来的session_id，此session_id是访问alertRemind接口返回的session_id,这样每次存放code都是存放在同一个sessionid下，不然的话在不同窗口下启用的session_id是不一样的，会导致数据错乱
		//获取验证码
		$num = 4;
		$size = 20;
		$width = $height = 0;
		//vCode 字符数目，字体大小，图片宽度、高度
		!$width && $width = $num * $size * 4 / 5 + 15;
		!$height && $height = $size + 10;

		//设置验证码字符集合
		$str = "23456789abcdefghijkmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVW";
        $code = '';//保存获取的验证码
        //查日志表，查出最近一次的code值,刷新操作除外
        if($getCodeType=='') {
            $recentCode = DB::table('operation_log')->where('user_id', $userId)->where('table', 'getCode')->orderBy('created_at', 'desc')->first();
            if ($recentCode && $recentCode->input) {
                $inputInfo = json_decode($recentCode->input);
                $code = isset($inputInfo->code) ? $inputInfo->code : '';
            }
        }

		if(empty($code)){
            //随机选取字符
            for ($i = 0; $i < $num; $i++) {
                $code .= $str[mt_rand(0, strlen($str)-1)];
            }
        }
        $_SESSION["VerifyCode_".$userId]=$code;
        $this->saveOperationLog('getCode', 0, array('code'=>$code,'session_key'=>"VerifyCode_".$userId,'userId'=>$userId,'sessionId'=>session_id()));//操作插入日志表中
		//创建验证码画布
		$im = imagecreatetruecolor($width, $height);

		//背景色
		$back_color = imagecolorallocate($im, mt_rand(0,100),mt_rand(0,100), mt_rand(0,100));

		//文本色
		$text_color = imagecolorallocate($im, mt_rand(100, 255), mt_rand(100, 255), mt_rand(100, 255));

		imagefilledrectangle($im, 0, 0, $width, $height, $back_color);
		// 画干扰线
		for($i = 0;$i < 5;$i++) {
			$font_color = imagecolorallocate($im, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255));
			imagearc($im, mt_rand(- $width, $width), mt_rand(- $height, $height), mt_rand(30, $width * 2), mt_rand(20, $height * 2), mt_rand(0, 360), mt_rand(0, 360), $font_color);
		}

		// 画干扰点
		for($i = 0;$i < 50;$i++) {
			$font_color = imagecolorallocate($im, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255));
			imagesetpixel($im, mt_rand(0, $width), mt_rand(0, $height), $font_color);
		}

		//随机旋转角度数组
		$array=array(5,4,3,2,1,0,-1,-2,-3,-4,-5);

		// 输出验证码
//		 imagefttext(image, size, angle, x, y, color, fontfile, text);
		imagestring($im, 5, 25, 8, $code, $text_color);
//		@imagefttext($im, $size , array_rand($array), 12, $size + 6, $text_color, 'c:\WINDOWS\Fonts\simsun.ttc', $code);
		//no-cache在每次请求时都会访问服务器
		//max-age在请求1s后再次请求会再次访问服务器，must-revalidate则第一发送请求会访问服务器，之后不会再访问服务器
		// header("Cache-Control: max-age=1, s-maxage=1, no-cache, must-revalidate");
//		header("Cache-Control: no-cache");
		header("Content-type: image/png;charset=gb2312");
		//将图片转化为png格式
		imagepng($im);
		imagedestroy($im);
	}

    /*
     * 验证填写的验证码是否正确
     * 验证验证码是否输入正确
     */
    public function verifyCode()
    {
        header('Access-Control-Allow-Origin:*');
        session_start();//开启session获取验证码数据
        $userId = isset($_REQUEST['userId']) && $_REQUEST['userId'] ? $_REQUEST['userId'] : 0;
        $sessionId = isset($_REQUEST['sessionId']) && $_REQUEST['sessionId'] ? $_REQUEST['sessionId'] : 0;
        $code = isset($_REQUEST['code']) && $_REQUEST['code'] ? trim($_REQUEST['code']) : '';
        session_id($sessionId);
        $recentCode = DB::table('operation_log')->where('user_id', $userId)->where('table', 'getCode')->orderBy('created_at', 'desc')->first();
        $trueCode = '';
        if ($recentCode && $recentCode->input) {
            $inputInfo = json_decode($recentCode->input);
            $trueCode = isset($inputInfo->code) ? $inputInfo->code : '';
        }
//        $trueCode = isset($_SESSION["VerifyCode_".$userId]) ? $_SESSION["VerifyCode_".$userId] : '';
        $result['status'] = 0;
        $result['code'] = $code;
        $result['truecode'] = $trueCode;
        if($code == $trueCode){
            $result['status'] = 1;
        }
        $result['userId'] = $userId;
        $this->saveOperationLog('verifyCode', 0, array('session'=>$_SESSION,'userId'=>$userId,'code'=>$code,'truecode'=>$trueCode,'sessionkey'=>"VerifyCode_".$userId,'sessionid'=>session_id()));//操作插入日志表中
        echo json_encode($result);
    }

}
