<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\RsgRequest;
use App\Models\RsgProduct;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\App;
use DB;

class ProductController extends Controller
{
	public function __construct()
	{

	}

	public function detail(Request $request)
	{
		$id = intval($_REQUEST['id']);
		$date = $this->getDefaultDate(date('Y-m-d'));
		$user = isset($_GET['user']) ? $_GET['user'] : '';
		$user = explode('V',$user);
		$userid = isset($user[1]) && $user[1] ? $user[1] : 0;
		$pageid = isset(getPageidByUserid()[$userid]) ? getPageidByUserid()[$userid] : getPageidByUserid()[0];
		session()->put('pageid',$pageid);


		$data = RsgProduct::where('asin.id',$id)->where('created_at',$date)
			->join('asin', function ($join) {
				$join->on('rsg_products.asin', '=','asin.asin')->on('rsg_products.site', '=','asin.site')->on('rsg_products.sellersku','=','asin.sellersku');
			})->take(1)->select('asin.id as asin_id','rsg_products.*')->get()->toArray();
        
        $site = array_get($data,'0.site');
        $p_id = array_get($data,'0.id');

		$where_product = " and site = '".$site."' and created_at = '".$date."' and cast(rsg_products.sales_target_reviews as signed) - cast(rsg_products.requested_review as signed) > 0 and product_img !='' and order_status!=-1";
		$orderby = " order by rsg_products.order_status desc,score desc,id desc ";
		if($site=='www.amazon.com'){
			$where_product .= ' and price < 100 ';
 		}
		$limit = 10;//默认显示10条数据
		if($site=='www.amazon.co.jp'){//日本站点限制显示置顶产品
			$where_product .= ' and order_status = 1 ';
			$limit = 4;//日本站点最多显示4条数据
		}

		$sql = "
        SELECT rsg_products.id as id,(status_score*type_score*level_score*rating_score*review_score*days_score)  as score
            from rsg_products  
            left join (
				select id,
					case post_status
						WHEN 1 then 1
						WHEN 2 then 2
						ELSE 0 END as status_score,
					case post_type
						WHEN 1 then 1*20
						WHEN 2 then 0.5*20
						ELSE 0 END as type_score,
				   	if(stock_days<60,0,1) as days_score,
					case sku_level
						WHEN 'S' then 1
						WHEN 'A' then 0.6
						WHEN 'B' then 0.2
						ELSE 0 END as level_score,
					case review_rating
						WHEN 5 then 1
						WHEN 4.9 then 1
						WHEN 4.8 then 2
						WHEN 4.7 then 4
						WHEN 4.6 then 2
						WHEN 4.5 then 1
						WHEN 4.4 then 1
						WHEN 4.3 then 3
						WHEN 4.2 then 5
						WHEN 4.1 then 4
						WHEN 0 then 1
						ELSE 0 END as rating_score,
					if(site='www.amazon.com',
						case 
							WHEN number_of_reviews < 100 then 10
							WHEN number_of_reviews >= 100 and number_of_reviews < 400 then 7
							WHEN number_of_reviews >= 400 and number_of_reviews < 1000 then 4
							WHEN number_of_reviews >= 1000 and number_of_reviews < 4000 then 1
							WHEN number_of_reviews >= 4000 then 0
							END,
						case 
							WHEN number_of_reviews < 40 then 10
							WHEN number_of_reviews >= 40 and number_of_reviews < 100 then 7
							WHEN number_of_reviews >= 100 and number_of_reviews < 400 then 4
							WHEN number_of_reviews >= 400 and number_of_reviews <= 1000 then 1 
							WHEN number_of_reviews > 1000 then 0
							END 
					)as review_score 
				from rsg_products 
				where created_at = '".$date."'  
			) as rsg_score on rsg_score.id=rsg_products.id 
			where 1 = 1 {$where_product} 
			{$orderby} limit 10";
		$_products = DB::select($sql);
		$ids = array();
		//取出前十条数据的id
		foreach($_products as $key=>$val){
			$ids[] = $val->id;
		}
        if(!in_array($p_id,$ids)) return redirect('/');
		if($data){
			$data = $data[0];
			$data['product_content'] = htmlspecialchars($data['product_content']);
			$data['product_summary'] = nl2br($data['product_summary']);
			$data['price'] = ltrim($data['price'],0);
			$data['task'] = $data['sales_target_reviews']-$data['requested_review'];
			$data['product_summary'] = json_decode(str_replace("'",'"',$data['product_summary']),TRUE);
			if($data['product_summary']){
				$data['product_summary'] = implode("<br/>",$data['product_summary']);
			}
		}

		$customer_email = $request->route('customer_email');
		if(session()->get('customer_email')){
			$customer_email = session()->get('customer_email');
		}else{
			$validator = Validator::make(array('customer_email'=>$customer_email), array('customer_email'=>array('email')));
			if ($validator->passes()) session()->put('customer_email',$customer_email);
		}

		$from = isset($_REQUEST['from']) ? $_REQUEST['from'] : '';

		return view('productDetail',['data'=>$data,'customer_email'=>$customer_email,'from'=>$from,'user_id'=>$userid]);
	}


}
