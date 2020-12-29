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
    //产品详情
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
            $productData['category'] = [];
    		foreach($categoryIds as $ck=>$cv){
                $productData['category'][$cv] = isset($productCategory[$cv]) && $productCategory[$cv] ? $productCategory[$cv] : $cv;
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
    //某一个类目下的所有产品
    public function productCategory(Request $request)
    {
        $id = isset($_REQUEST['id']) && $_REQUEST['id'] ? $_REQUEST['id'] : '';
        $productData = array();
        $length = 12;//每页12条数据
        $page = isset($_REQUEST['page']) && $_REQUEST['page'] ? $_REQUEST['page'] : 1;

        $datas = DB::table('ctg_products');
        if($id){//有产品分类id就只查询该分类下的产品
            $datas = $datas->whereRaw("find_in_set($id,category_ids)");
        }
        $totalRecords = $datas->count();
        $totalPage = ceil($totalRecords/$length);//总记录数除以每页数再向上取整=总共的页数
        $page = $page < 1 ? 1 : $page;//传过来的页数小于1的时候取第一页的数据展示
        $page = $page > $totalPage ? $totalPage : $page;//传过来的页数大于最大页数的话，取最后一页的数据展示
        $offset = ($page-1)*$length;//忽略前面多少条数据不查出来，第二页的话就是12
        $startpage = $page-2;//页面上点击链接跳转到n页的起始页
        $startpage = $startpage<1 ? 1 : $startpage;
        for($i = $startpage; $i<=$totalPage; $i = $i + 1){
            if($i<=$startpage+4){//最多显示5页的按钮
                $pageArray[] = $i;
            }
        }
        $return = array('id'=>$id,'current'=>$page,'total'=>$totalPage,'page'=>$pageArray);

        $_productData = $datas->offset($offset)->limit($length)->get()->toArray();
        foreach($_productData as $key=>$val){
            $productData[$key] = (array)$val;
            $imageArr = explode(',',$val->images);
            $productData[$key]['price'] = sprintf("%.2f",$val->price);
            //关联产品展示的图片
            if($imageArr){
                $productData[$key]['img'] = $imageArr[0];
            }else{
                $productData[$key]['img'] = '';
            }
        }
        return view('product.productList',['productData'=>$productData,'return'=>$return]);
    }


}
