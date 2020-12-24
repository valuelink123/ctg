<?php
function getStepStatus(){
	return array(
		'1'  => 'Check Customer',
		'2'  => 'Request Reject',
		'3'  => 'Submit Paypal',
		'4'  => 'Check Paypal',
		'5'  => 'Submit Purchase',
		'6'  => 'Check Purchase',
		'7'  => 'Submit Review',
		'8'  => 'Check Review',
		'9'  => 'Completed'
	);
}

/*
 * 通过VOP系统的userid分配各自的Pageid
 * 默认值为944899235710664
 */
function getPageidByUserid(){
	return array(
		'0' => '944899235710664',
		'297' => '103260144485429',
		'213' => '106240350850326',
		'233' => '104002604409985',
		'241' => '110651963738568',
		'262' => '106878964102439',
		'296' => '104258491050597',
		'307' => '101595674654584',
		'306' => '105267130949028',
		'320' => '104106071066369',
		'319' => '101863614627144',
		'318' => '105267130949028',
		'324' => '102418371248137',
		'322' => '107470367403393',
		'323' => '101323701359773',
		'325' => '105536240932430',
		'198' => '104049124417154',
	);
}


/*
 * 客户参与RSG资格判断为红色Unaviliable的时候
 * 因何种原因被判为红色(rsg_status_explain)对应的提示词语
 * rsg_status_explain对应的键值对，提示词语分为RSG官网提示和VOP提示
 * 1，标签为黑名单、客户账号留评被限制的客户
 * 2，有过已付款未购买情况的客户
 * 3，留差评客户
 * 4，最近30天有参与4次RSG
 * 5，留评率低于90%的客户
 * 6，上个活动不是Completed状态
 */
function getCrmRsgStatusArr()
{
    $data = array(
        1 => array(
            'rsg' => 'Sorry,you are not eligible for participation,please contact customer service for details',
        ),
        2 => array(
            'rsg' => 'Sorry,you are not eligible for participation,please contact customer service for details',
        ),
        3 => array(
            'rsg' => 'Sorry,you are not eligible for participation,please contact customer service for details',
        ),
        4 => array(
            'rsg' => 'You have reached the maximum number of participations this month,please apply again next week',
        ),
        5 => array(
            'rsg' => 'Sorry,you are not eligible for participation,please contact customer service for details',
        ),
        6 => array(
            'rsg' => 'Your last application is not over yet,please complete it first',
        ),
    );
    return $data;
}
