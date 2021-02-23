<?php
/*
 * 付费阅读异步回调
 */
include '../../../config.inc.php';
include 'function.php';
require_once 'libs/payjs.php';
date_default_timezone_set('Asia/Shanghai');
$db = Typecho_Db::get();
$options = Typecho_Widget::widget('Widget_Options');
$option=$options->plugin('TeePay');
$params = $_GET;
$params = paraFilter($params);
$params = argSort($params);
$md5Sigm = md5(createLinkstring($params) . $option->payjs_wxpay_key);
if($params['trade_status'] == 'TRADE_SUCCESS'){
    if($md5Sigm == $_GET['sign']){
        $updateItem = $db->update('table.teepay_fees')->rows(array('feestatus'=>1))->where('feeid=?',$params["out_trade_no"]);
        $updateItemRows= $db->query($updateItem);
        echo 'success';
        exit();
    }else{
        $db = Typecho_Db::get();
        $updateItem = $db->update('table.teepay_fees')->rows(array('feestatus'=>2))->where('feeid=?',$_GET['out_trade_no']);
        $updateItemRows= $db->query($updateItem);
        echo(sign($gdata));
        echo 'fail';exit();
    }
}

?>