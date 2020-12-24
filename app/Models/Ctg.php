<?php
/**
 * Created by PhpStorm.
 * Date: 18.9.21
 * Time: 16:31
 */

namespace App\Models;

use App\Asin;
use App\Classes\SapRfcRequest;
use App\Exceptions\HypocriteException;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Ctg extends Model {
    protected $primaryKey = null;
    public $incrementing = false; // 主键不是数字，需要有此声明，否则 order_id 被截断
    protected $table = 'ctg';
    // protected $guarded = ['id']; // 黑名单模式
    protected $fillable = ['order_id', 'gift_sku', 'name', 'email', 'phone', 'address', 'note', 'rating', 'processor','nonctg_id','channel'];

    /**
     * 1.检查订单号有效性
     * 2.根据 sap_seller_id 分配处理人
     * 3.写入 ctg、ctg_order、ctg_order_item 三个表
     * @throws HypocriteException
     */
    public static function add($row) {
        $return = array('status'=>1,'msg'=>'');

        if (empty($row['order_id'])){
            $return['msg'] = 'ORDER ID IS UNSET';
            $return['status'] = 0;
            return $return;
        }

        $row = array_map('trim', $row);

        try {

            $sap = new SapRfcRequest();

            $order = $sap->getOrder(['orderId' => $row['order_id']]);

            $order = SapRfcRequest::sapOrderDataTranslate($order);

            if (empty($order['orderItems'])) {
                $return['msg'] = 'Order Info Error.';
                $return['status'] = 0;
                return $return;
            }

        } catch (\Exception $e) {
            $return['msg'] = $e->getMessage() . ' For help, please mail to support@claimgiftsnow.com';
            $return['status'] = 0;
            return $return;
        }
        try {

            DB::beginTransaction();

            // 时区不明确 todo
            $ctgRow = self::select('created_at')->where('created_at', '>', $order['PurchaseDate'])->where('order_id', $row['order_id'])->limit(1)->lockForUpdate()->first();

            if (!empty($ctgRow)) {
                DB::rollback();
                $return['msg'] = "Order ID is duplicate, submitted in {$ctgRow['created_at']}.";
                $return['status'] = 0;
                return $return;
            }

            $item = current($order['orderItems']);
            $asinRow = DB::table('asin')->select('sap_seller_id')->where('site', $item['MarketPlaceSite'])->where('asin', $item['ASIN'])->where('sellersku', $item['SellerSKU'])->first();

            $row['processor'] = isset($row['processor']) ? $row['processor'] : 0;

            if (!empty($asinRow->sap_seller_id) && $row['processor']==0) {

                $user = User::select('id')->where('sap_seller_id', $asinRow->sap_seller_id)->limit(1)->first();

                if (!empty($user->id)) {
                    $row['processor'] = $user->id;
                }
            }

            $obj = self::create($row);

            // $find = [
            //     // 'created_at' => $order['created_at'],
            //     'MarketPlaceId' => $order['MarketPlaceId'],
            //     'SellerId' => $order['SellerId'],
            //     'AmazonOrderId' => $order['AmazonOrderId'],
            // ];
            // 去重效率成本太高，放弃；


            foreach ($order['orderItems'] as $item) {
                $item['created_at'] = $obj['created_at'];
                $item['updated_at'] = date('Y-m-d H:i:s');
                // CtgOrderItem::updateOrCreate($find, $item);
                DB::table('ctg_order_item')->insert($item);
            }
            unset($order['orderItems']);
            $order['created_at'] = $obj['created_at'];
            $order['updated_at'] = date('Y-m-d H:i:s');
            // CtgOrder::updateOrCreate($find, $order);
            DB::table('ctg_order')->insert($order);
            DB::commit();
            if($obj){
                $return['status'] = 1;
                $return['obj'] = $obj;
            }
            return $return;

        } catch (\Exception $e) {
            $return['msg'] = $e->getMessage() . ' For help, please mail to support@claimgiftsnow.com';
            $return['status'] = 0;
            return $return;
        }
        return $return;
    }
}
