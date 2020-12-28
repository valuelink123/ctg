<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use DB;

class ProductController extends Controller
{
	public function __construct()
	{

	}

	public function detail(Request $request)
	{
		$id = isset($_REQUEST['id']) && $_REQUEST['id'] ? $_REQUEST['id'] : '';
    	$productData = (array)DB::table('ctg_products')->where('id',$id)->first();
    	if($productData){
    		$productData['summary'] = nl2br($productData['summary']);
    		$productData['summary'] = json_decode(str_replace("'",'"',$productData['summary']),TRUE);
			if($productData['summary']){//简要概述
				$productData['summary'] = implode("\n",$productData['summary']);
			}
			$productData['content'] = htmlspecialchars($productData['content']);//产品内容
    		$productData['imageArr'] = explode(',',$productData['images']);//把图片变成数组
            $productData['price'] = sprintf("%.2f",$productData['price']);//价格格式化
    		$productCategory = $this->getProductCategory();//得到产品类别
    		$categoryIds = explode(',',$productData['category_ids']);
    		$productData['categoryName'] = '';
    		foreach($categoryIds as $ck=>$cv){
    			$productData['categoryName'].=(isset($productCategory[$cv]) && $productCategory[$cv] ? $productCategory[$cv] : $cv).',';
    		}
    		$productData['categoryName'] = trim($productData['categoryName'],',');

    		//查询详情页下面的关联产品数据
    		$relateIds = explode(',',$productData['related_product_ids']);
    		$relateData = DB::table('ctg_products')->whereIn('id',$relateIds)->limit(4)->get()->toArray();
            $productData['relate'] = array();
    		foreach($relateData as $key=>$val){
    			$productData['relate'][$key] = (array)$val;
    			$imageArr = explode(',',$val->images);
    			$productData['relate'][$key]['price'] = sprintf("%.2f",$val->price);
    			//关联产品展示的图片
                if($imageArr){
                    $productData['relate'][$key]['img'] = $imageArr[0];
                }else{
                	$productData['relate'][$key]['img'] = '';
                }

    		}
    	}else{
    		$msg = 'Please enter the correct product ID';
    		return view('error',['msg'=>$msg]);
    	}
		return view('product.detail',['productData'=>$productData]);
	}


}
