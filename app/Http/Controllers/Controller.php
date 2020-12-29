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
    //检验传过来的订单号是否符合要求
    public function checkOrderData($orderId) {
        $return['status'] = 1;
        $return['msg'] = '';
        try {
            //查询亚马逊订单是否存在
            $amazon_db = mysqli_connect('valuelink.cluster-cc2upz14y6dm.ap-east-1.rds.amazonaws.com','valuelink_vop_read','fq!Lmrp899zXF$lY','valuelink');
            $result = mysqli_query($amazon_db,"select * from orders where amazon_order_id='$orderId' limit 1");
            $order = mysqli_fetch_array($result);

            $exists_ctg = $exists_rsg = false;
            //查询是否申请过CTG
            $ctgData = DB::table('ctg_order')->where('AmazonOrderId',$orderId)->first();
            if ($ctgData) {
                $exists_ctg = true ;
            }
            //查询是否申请过RSG
            $ctgData = DB::table('rsg_requests')->where('amazon_order_id',$orderId)->first();
            if ($ctgData) {
                $exists_rsg = true ;
            }
        } catch (\Exception $e) {
            $return['status'] = 0;
            $return['msg'] = 'System network error';
            return $return;
        }

        if(empty($order)){
            $return['status'] = 0;
            $return['msg'] = 'Incorrect order number';
            return $return;
        }
        if($exists_ctg || $exists_rsg){
            $return['status'] = 0;
            $return['msg'] = 'Your order cannot participate in this event';
            return $return;
        }
        return $return;
    }
    //通过sap获取到订单数据，调用VOP系统的接口
    public function getOrderData($orderId)
    {
        $url = 'http://vlerp.com:88//getOrderDataBySap?orderid='.$orderId;
        $orderData = file_get_contents($url);
        $orderData = json_decode($orderData,true);
        $result = isset($orderData['data']) ? $orderData['data'] : array();
        $result = $this->sapOrderDataTranslate($result);
        return $result;
    }
    //对从sap获取过来的订单数据进行处理，重组成我们想要的格式
    public function sapOrderDataTranslate($data) {

        if (empty($data['O_ITEMS'])) return ['orderItems' => []];

        $order = array(
            'SellerId' => $data['SELLERID'],
            'MarketPlaceId' => $data['ZMPLACEID'],
            'AmazonOrderId' => $data['ZAOID'],
            'SellerOrderId' => $data['ZSOID'],
            'ApiDownloadDate' => date('Y-m-d H:i:s', strtotime($data['ALOADDATE'] . $data['ALOADTIME'])),
            'PurchaseDate' => date('Y-m-d H:i:s', strtotime($data['PCHASEDATE'] . $data['PCHASETIME'])),
            'LastUpdateDate' => date('Y-m-d H:i:s', strtotime($data['LUPDATEDATE'] . $data['LUPDATETIME'])),
            'OrderStatus' => $data['ORSTATUS'],
            'FulfillmentChannel' => $data['FCHANNEL'],
            'SalesChannel' => strtolower($data['SCHANNEL']),
            'OrderChannel' => $data['OCHANNEL'],
            'ShipServiceLevel' => $data['SHIPLEVEL'],
            'Name' => $data['ZNAME'],
            'AddressLine1' => $data['ADDR1'],
            'AddressLine2' => $data['ADDR2'],
            'AddressLine3' => $data['ADDR3'],
            'City' => $data['ZCITY'],
            'County' => $data['ZCOUNTRY'],
            'District' => $data['ZDISTRICT'],
            'StateOrRegion' => $data['ZSOREGION'],
            'PostalCode' => $data['ZPOSCODE'],
            'CountryCode' => $data['ZCOUNTRYCODE'],
            'Phone' => $data['ZPHONE'],
            'Amount' => $data['ZAMOUNT'],
            'CurrencyCode' => $data['ZCURRCODE'],
            'NumberOfItemsShipped' => $data['NISHIPPED'],
            'NumberOfItemsUnshipped' => $data['NIUNSHIPPED'],
            'PaymentMethod' => $data['PMETHOD'],
            'BuyerName' => $data['BUYNAME'],
            'BuyerEmail' => $data['BUYEMAIL'],
            'ShipServiceLevelCategory' => $data['SSCATEGORY'],
            'EarliestShipDate' => ($data['ESDATE'] > 0) ? date('Y-m-d H:i:s', strtotime($data['ESDATE'] . $data['ESTIME'])) : '',
            'LatestShipDate' => ($data['LSDATE'] > 0) ? date('Y-m-d H:i:s', strtotime($data['LSDATE'] . $data['LSTIME'])) : '',
            'EarliestDeliveryDate' => ($data['EDDATE'] > 0) ? date('Y-m-d H:i:s', strtotime($data['EDDATE'] . $data['EDTIME'])) : '',
            'LatestDeliveryDate' => ($data['LDDATE'] > 0) ? date('Y-m-d H:i:s', strtotime($data['LDDATE'] . $data['LDTIME'])) : '',
        );

        $MarketPlaceSite = 'www.' . $order['SalesChannel'];

        $orderItemData = [];

        foreach ($data['O_ITEMS'] as $sdata) {
            $orderItemData[] = array(
                'SellerId' => $sdata['SELLERID'],
                'MarketPlaceId' => $sdata['ZMPLACEID'],
                'AmazonOrderId' => $sdata['ZAOID'],
                'OrderItemId' => $sdata['ZORIID'],
                'Title' => $sdata['TITLE'],
                'QuantityOrdered' => intval($sdata['QORDERED']),
                'QuantityShipped' => intval($sdata['QSHIPPED']),
                'GiftWrapLevel' => $sdata['GWLEVEL'],
                'GiftMessageText' => $sdata['GMTEXT'],
                'ItemPriceAmount' => round($sdata['IPAMOUNT'], 2),
                'ItemPriceCurrencyCode' => $sdata['IPCCODE'],
                'ShippingPriceAmount' => round($sdata['SPAMOUNT'], 2),
                'ShippingPriceCurrencyCode' => $sdata['SPCCODE'],
                'GiftWrapPriceAmount' => round($sdata['GWPAMOUNT'], 2),
                'GiftWrapPriceCurrencyCode' => $sdata['GWPCCODE'],
                'ItemTaxAmount' => round($sdata['ITAMOUNT'], 2),
                'ItemTaxCurrencyCode' => $sdata['ITCCODE'],
                'ShippingTaxAmount' => round($sdata['STAMOUNT'], 2),
                'ShippingTaxCurrencyCode' => $sdata['STCCODE'],
                'GiftWrapTaxAmount' => round($sdata['GWTAMOUNT'], 2),
                'GiftWrapTaxCurrencyCode' => $sdata['GWTCCODE'],
                'ShippingDiscountAmount' => round($sdata['SDAMOUNT'], 2),
                'ShippingDiscountCurrencyCode' => $sdata['SDCCODE'],
                'PromotionDiscountAmount' => round($sdata['PDAMOUNT'], 2),
                'PromotionDiscountCurrencyCode' => $sdata['PDCCODE'],
                'PromotionIds' => $sdata['PROMOID'],
                'CODFeeAmount' => round($sdata['CFAMOUNT'], 2),
                'CODFeeCurrencyCode' => $sdata['CFCCODE'],
                'CODFeeDiscountAmount' => round($sdata['CFDAMOUNT'], 2),
                'CODFeeDiscountCurrencyCode' => $sdata['CFDCCODE'],
                'ASIN' => $sdata['ZASIN'],
                'SellerSKU' => $sdata['ZSSKU'],
                'MarketPlaceSite' => $MarketPlaceSite
            );
        }

        $order ['orderItems'] = $orderItemData;
        return $order;
    }

}
