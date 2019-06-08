<?php
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
header('Content-Type:text/html;charset=utf8');
date_default_timezone_set('Asia/Shanghai');

include '../config/config.php';
include '../common/Helps.php';

$mch_no = $config['mch_no'];
$out_trade_no = time() + mt_rand(1000, 9999);
$amount = $_POST['amount'];
if ($amount > 50000 || $amount < 100) {
    die('充值金额需大于100元小于50000元');
} else {
    $amount = $_POST['amount'] * 100;
}
$payment_type = $_POST['payment_type'];
$notify_url = 'http://' . $_SERVER['HTTP_HOST'] . '/notify.php';
$key = $config['key'];

$data = "amount=" . $amount . "&mch_no=" . $mch_no . "&notify_url=" . $notify_url . "&out_trade_no=" . $out_trade_no . "&payment_type=" . $payment_type . "&" . $key;
$sign = md5($data);

$url = "http://api.hlpay8.com/api/createOrder?";
$url .= 'amount=' . $amount . '&mch_no=' . $mch_no . '&notify_url=' . $notify_url . '&out_trade_no=' . $out_trade_no . '&payment_type=' . $payment_type . '&sign=' . $sign;
$helps = new \Common\Helps();
$result = $helps->getcurl($url);
$deresult = json_decode($result);


$filename = "../rizhi/" . 'request-' . $out_trade_no . ".txt";
$helps->myfwrite($filename,$data);

if ($deresult->code == 0) {
    header("location:" . $deresult->data);
} else {
    echo $deresult->data;
}
?>
<!--<!doctype html>
<html>
<head>
    <meta charset="utf8">
    <title>正在转到付款页</title>
</head>
<body onLoad="document.pay.submit()">
<form name="pay" action="https://aa.39team.com/apisubmit" method="post">
    <input type="hidden" name="version" value="<?php /*echo $version */ ?>">
    <input type="hidden" name="customerid" value="<?php /*echo $customerid */ ?>">
    <input type="hidden" name="userid" value="<?php /*echo $userid */ ?>">
    <input type="hidden" name="sdorderno" value="<?php /*echo $sdorderno */ ?>">
    <input type="hidden" name="total_fee" value="<?php /*echo $total_fee */ ?>">
    <input type="hidden" name="paytype" value="<?php /*echo $paytype */ ?>">
    <input type="hidden" name="notifyurl" value="<?php /*echo $notifyurl */ ?>">
    <input type="hidden" name="returnurl" value="<?php /*echo $returnurl */ ?>">
    <input type="hidden" name="remark" value="<?php /*echo $remark */ ?>">
    <input type="hidden" name="bankcode" value="<?php /*echo $bankcode */ ?>">
    <input type="hidden" name="sign" value="<?php /*echo $sign */ ?>">
</form>
</body>
</html>-->
