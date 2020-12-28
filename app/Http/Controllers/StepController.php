<?php

namespace App\Http\Controllers;
// use \DrewM\MailChimp\MailChimp;
use Illuminate\Http\Request;
use App\Classes\SapRfcRequest;
use App\Models\Ctg;
use DB;

class StepController extends Controller
{

    //第0步
    public function stepZero(Request $request)
    {
        $msg = '';
        $insert['order_id'] = $orderid = isset($_REQUEST['orderid']) ? $_REQUEST['orderid'] : '';
        $insert['email'] = $email = isset($_REQUEST['email']) ? $_REQUEST['email'] : '';
        $insert['name'] = isset($_REQUEST['uname']) ? $_REQUEST['uname'] : '';

        if (empty($orderid) || empty($email)) {
            $msg = 'Please confirm your order number.';
            return view('error',['msg'=>$msg]);
        }

        if (!preg_match('#^\d{3}-\d{7}-\d{7}$#', $orderid)) {
            $msg = 'Please confirm your order number .';
            return view('error',['msg'=>$msg]);
        }

        $insert['created_at'] = date('Y-m-d H:i:s');
        $requestId = Db::table('ctg_request')->insertGetId($insert);//客户申请的数据插入到表ctg_request中
        if($requestId){
            $sap = new SapRfcRequest();
            $sapOrderInfo = SapRfcRequest::sapOrderDataTranslate($sap->getOrder(['orderId' => $orderid]));
            $asin = isset($sapOrderInfo['orderItems']['0']['ASIN']) ? $sapOrderInfo['orderItems']['0']['ASIN'] : '';
            if($asin){
                $addr[] = $sapOrderInfo['AddressLine1'];
                $addr[] = $sapOrderInfo['AddressLine2'];
                $addr[] = $sapOrderInfo['AddressLine3'];
                $addr[] = $sapOrderInfo['City'];
                $addr[] = $sapOrderInfo['County'];
                $addr[] = $sapOrderInfo['District'];
                $addr[] = $sapOrderInfo['StateOrRegion'];
                $addr[] = $sapOrderInfo['PostalCode'];
                $addr[] = $sapOrderInfo['CountryCode'];
                $address = implode(PHP_EOL, $addr);
                $orderInfo = array('order_id'=>$orderid,'product_title'=>$sapOrderInfo['orderItems']['0']['Title'],'name'=>$sapOrderInfo['Name'],'address'=>$address);
                //查ctg_gift_contrast表，购买的asin跟礼品产品的对应关系
                $product_id = DB::table('ctg_gift_contrast')->where('asin',$asin)->pluck('product_id')->first();//没有数据的时候为'',有数据的时候直接是id
                $product_id = $product_id > 0 ? $product_id : 1;//没有配置礼品对应关系就给予product_id=1
                DB::table('ctg_request')->where('id',$requestId)->update(['product_id'=>$product_id]);
                $productInfo = (array)DB::table('ctg_products')->where('id',$product_id)->first();//查出产品信息
                $imageArr = explode(',',$productInfo['images']);
                if($imageArr){
                    $productInfo['img'] = $imageArr[0];
                }
            }else{
                $msg = 'Please confirm that this order number exists';
            }
        }else{
            $msg = 'System error';
        }
        if($msg){
            return view('error',['msg'=>$msg]);
        }
        $args['title'] = 'Claim the gift 0 - Claim The Gift';
        return view('step.step_0',['orderInfo'=>$orderInfo,'productInfo'=>$productInfo,'requestId'=>$requestId,'args'=>$args]);
    }
    //第一步
    public function stepOne(Request $request)
    {
        $return['status'] = 1;
        $return['msg'] = $msg = '';
        $requestId = isset($_REQUEST['requestId']) ? $_REQUEST['requestId'] : '';
        if ($request->isMethod('POST')) {
            $inputData['input_1'] = isset($_POST['input_1']) ? $_POST['input_1'] : '';
            $inputData['input_2'] = isset($_POST['input_2']) ? $_POST['input_2'] : '';
            $inputData['input_3'] = isset($_POST['input_3']) ? $_POST['input_3'] : '';

            $insert['request_id'] = $requestId;
            $insert['step_1'] = json_encode($inputData);
            $insert['created_at'] = date('Y-m-d H:i:s');
            $insert['updated_at'] = date('Y-m-d H:i:s');
            $stepData = DB::table('ctg_step')->where('request_id',$requestId)->first();
            if(empty($stepData)){
                $res = DB::table('ctg_step')->insert($insert);
                if(!$res){
                    $return['msg'] = 'System error';
                    $return['status'] = 0;
                }
            }else{
                $return['msg'] = 'RequestId has already applied';
                $return['status'] = 0;
            }
            return $return;
        }

        $_data = $this->getProductInfoByRequestId($requestId);
        $productInfo = $_data['productInfo'];
        $msg = isset($_data['msg']) && $_data['msg'] ? $_data['msg'] : $msg;
        if($msg){
            return view('error',['msg'=>$msg]);
        }
        $args['title'] = 'Claim The Gift Step 1 – Claim The Gift';
        return view('step.step_1',['productInfo'=>$productInfo,'requestId'=>$requestId,'args'=>$args]);
    }
    //第二步
    public function stepTwo(Request $request)
    {
        $return['status'] = 1;
        $return['msg'] = $msg = '';
        $requestId = isset($_REQUEST['requestId']) ? $_REQUEST['requestId'] : '';
        if ($request->isMethod('POST')) {
            $inputData['input_1'] = isset($_POST['input_1']) ? $_POST['input_1'] : '';
            $inputData['input_2'] = isset($_POST['input_2']) ? $_POST['input_2'] : '';
            $inputData['input_3'] = isset($_POST['input_3']) ? $_POST['input_3'] : '';
            $inputData['input_4'] = isset($_POST['input_4']) ? $_POST['input_4'] : '';

            $update['step_2'] = json_encode($inputData);
            $update['updated_at'] = date('Y-m-d H:i:s');

            $res = DB::table('ctg_step')->where('request_id',$requestId)->update($update);
            if(!$res){
                $return['msg'] = 'System error';
                $return['status'] = 0;
            }
            return $return;
        }
        $_data = $this->getProductInfoByRequestId($requestId);
        $productInfo = $_data['productInfo'];
        $msg = isset($_data['msg']) && $_data['msg'] ? $_data['msg'] : $msg;
        if($msg){
            return view('error',['msg'=>$msg]);
        }
        $args['title'] = 'Claim the gift step 2 - Claim The Gift';
        return view('step.step_2',['productInfo'=>$productInfo,'requestId'=>$requestId,'args'=>$args]);
    }
    //第三步
    public function stepThree(Request $request)
    {
        $return['status'] = 1;
        $return['msg'] = $msg = '';
        $requestId = isset($_REQUEST['requestId']) ? $_REQUEST['requestId'] : '';
        if ($request->isMethod('POST')) {
            $inputData['input_1'] = isset($_POST['input_1']) ? $_POST['input_1'] : '';

            $update['step_3'] = json_encode($inputData);
            $update['updated_at'] = date('Y-m-d H:i:s');

            $res = DB::table('ctg_step')->where('request_id',$requestId)->update($update);
            if(!$res){
                $return['msg'] = 'System error';
                $return['status'] = 0;
            }
            return $return;
        }
        $step_2 = DB::table('ctg_step')->where('request_id',$requestId)->pluck('step_2')->first();
        if($step_2){
            $step_2 = (array)json_decode($step_2);
            $return['review'] = $step_2['input_4'];
        }else{
            $msg = 'Data exception';
            return view('error',['msg'=>$msg]);
        }
        $args['title'] = 'Claim the gift step 3 - Claim The Gift';
        return view('step.step_3',['requestId'=>$requestId,'return'=>$return,'args'=>$args]);
    }
    //第四步
    public function stepFour(Request $request)
    {
        $msg = $return['msg'] = '';
        $return['status'] = 1;
        $requestId = isset($_REQUEST['requestId']) ? $_REQUEST['requestId'] : '';
        if ($request->isMethod('POST')) {
            $sql = 'select *
                From ctg_request
                Left Join ctg_products on ctg_products.id = ctg_request.product_id
                Left Join ctg_step On ctg_step.request_id = ctg_request.id
                where ctg_request.id='.$requestId ;
            $_dataInfo = DB::select($sql);
            $dataInfo = (array)$_dataInfo[0];
            if($dataInfo){
                //先插入到ctg_step表的step_4字段中
                $inputData['name'] = isset($_POST['name']) ? $_POST['name'] : '';
                $inputData['addressLine1'] = isset($_POST['addressLine1']) ? $_POST['addressLine1'] : '';
                $inputData['addressLine2'] = isset($_POST['addressLine2']) ? $_POST['addressLine2'] : '';
                $inputData['city'] = isset($_POST['city']) ? $_POST['city'] : '';
                $inputData['phone'] = isset($_POST['phone']) ? $_POST['phone'] : '';
                $inputData['email'] = isset($_POST['email']) ? $_POST['email'] : '';

                $update['step_4'] = json_encode($inputData);
                $update['updated_at'] = date('Y-m-d H:i:s');

                $res = DB::table('ctg_step')->where('request_id',$requestId)->update($update);
                if(!$res){
                    $return['msg'] = 'System error';
                    $return['status'] = 0;
                }
                //处理数据插入到ctg表中
                $step_2 = $dataInfo['step_2'];
                $step_2 = (array)json_decode($step_2);
                $ctgInfo = [
                    'name' => $inputData['name'],
                    'address' => $inputData['addressLine1'].'<br/>'.$inputData['addressLine2'].'<br/>'.$inputData['city'],
                    'phone' => $inputData['phone'],
                    'email' => $inputData['email'],
                    'order_id' => $dataInfo['order_id'],
                    'gift_sku' => $dataInfo['sku'],
                    // 'rating' => $_SESSION['amz-free-gift']['user-marking-rating'],
                    'note' => implode(PHP_EOL, [
                        'How long have you been using our product?',//第二步的第一个问题
                        $step_2['input_1'],
                        'How do you like our product?',//第二步的第二个问题
                        $step_2['input_2'],
                        'How likely will you buy this product again? (1~10)',//第二步的第三个问题
                        $step_2['input_3'],
                        $step_2['input_4'],//第二步的第四个问题
                    ]),
                ];
                $return = Ctg::add($ctgInfo);//添加ctg数据
            }else{
                $return['msg'] = 'Invalid request ID';
                $return['status'] = 0;
            }
            return $return;
        }
        //get请求展示页面
        $requestInfo = (array)DB::table('ctg_request')->where('id',$requestId)->first();
        if($requestInfo){
            $orderid = $requestInfo['order_id'];
            $sap = new SapRfcRequest();
            $sapOrderInfo = SapRfcRequest::sapOrderDataTranslate($sap->getOrder(['orderId' => $orderid]));
            $asin = isset($sapOrderInfo['orderItems']['0']['ASIN']) ? $sapOrderInfo['orderItems']['0']['ASIN'] : '';
            if($asin){
                $requestInfo['AddressLine1'] = $sapOrderInfo['AddressLine1'];
                $requestInfo['AddressLine2'] = $sapOrderInfo['AddressLine2'];//City
                $requestInfo['city'] = $sapOrderInfo['City'] .' '.$sapOrderInfo['StateOrRegion'];
                $requestInfo['phone'] = $sapOrderInfo['Phone'];
            }else{
                $msg = 'Please confirm that this order number exists';
            }
        }else{
            $msg = 'System error';
        }
        if($msg){
            return view('error',['msg'=>$msg]);
        }
        $args['title'] = 'Claim the gift step 4 - Claim The Gift';
        return view('step.step_4',['requestInfo'=>$requestInfo,'args'=>$args]);
    }
    //第五步，申请成功的页面
    public function stepFive()
    {
        $args['title'] = 'Claim the gift step 5 - Claim The Gift';
        return view('step.step_5',['args'=>$args]);
    }


}
