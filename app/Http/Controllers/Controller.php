<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use DB;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    /*
     * 根据客户的requestid得到相对应的产品信息
     */
    public function getProductInfoByRequestId($requestId)
    {
        $msg = '';
        $productInfo = array();
        if($requestId){
            $product_id = DB::table('ctg_request')->where('id',$requestId)->pluck('product_id')->first();
            $productInfo = (array)DB::table('ctg_products')->where('id',$product_id)->first();//查出产品信息
            if($productInfo){
                $imageArr = explode(',',$productInfo['images']);
                $productInfo['img'] = '';
                if($imageArr){
                    $productInfo['img'] = $imageArr[0];
                }
            }else{
                $msg = 'Please confirm the availability of this product';
            }
        }else{
            $msg = 'Please confirm the request ID';
        }
        return array('msg'=>$msg,'productInfo'=>$productInfo);
    }
    /*
    * 得到产品类别的键值对array(id=>name)
     */
    public function getProductCategory()
    {
        $_data = DB::table('ctg_product_category')->get()->toArray();
        $data = array();
        if($_data){
            foreach($_data as $key=>$val){
                $data[$val->id] = $val->name;
            }
        }
        return $data;
    }

}
