<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8"> <!-- 請設定 utf8 另外這檔案儲存時也要是 UTF-8 檔首無BOM-->
<title>TEST玉山_WEB版</title>
</head>

<?php
header("Content-Type:text/html; charset=utf-8");

//時間+亂數混合KEY

$Ono= uniqid() . rand(1, 100000);
$MID="8089020012";//特約碼
$ReturnPHP="http://www.oceanworld.url.tw/YeLiu/esun_bank/test02.php";

//GET Field
$ProductNumber=$_GET['ProductNumber'];
$user_Name=$_GET['user_Name']; //使用者名稱
$user_address=$_GET['user_address']; //使用者地址
$user_phone=$_GET['user_phonenumber']; //使用者電話

//echo $ProductNumber;

$dbhost = 'localhost';
$dbuser = 'vhost109574';
$dbpass = 'ocwat339';
$dbname = 'vhost109574-3';	
$con = mysqli_connect($dbhost,$dbuser,$dbpass,$dbname) or die ("連線失敗");//連線設定

$ProductNumber_sql="SELECT `PorductName`,`Price`FROM `fishProduct` WHERE `Porduct_ID`='".$ProductNumber."'";
$Product_result = mysqli_query($con,$ProductNumber_sql) or die('查詢商品失敗');
$row = mysqli_fetch_array($Product_result);

$ProductName= $row['PorductName'] ;//商品名
$ProductPrice= $row['Price'] ;//價格


//ONO//訂單便號
//U//回傳網址
//MID:8089020012 	//商店號
//IC//分期代碼
//TA//交易金額
//TID//終端機代號(固定，分01 02)
//AA:MKGRYD4DEPMFCCQYFCCWFQ1S04ZQR1FW	//mackey

//回傳:RC=00,MID=8089020012, ONO=201702081012, LTD=20170302, LTT=141539, RRN=567061000001, AIR=238624, AN=552199******1872



//這段新增訂單資訊 訂單號.商店號.商品號.價格.使用者帳號
$trade_sql="INSERT INTO `fishTrade`(`order_ono`, `product`, `price`, `username`, `address`, `phone`) VALUES ('".$Ono."','".$ProductName."','".$ProductPrice."','".$user_Name."','".$user_address."','".$user_phone."')";
echo $trade_sql.'<br>';
$trade_result = mysqli_query($con,$trade_sql) or die('新增訂單失敗');



$data='{"ONO":'.$Ono.',"U":"'.$ReturnPHP.'","MID":"'.$MID.'","TA":"'.$ProductPrice.'","TID":"EC000001"}';
$mac=hash('sha256',$data.'MKGRYD4DEPMFCCQYFCCWFQ1S04ZQR1FW');
$ksn='1';



if($trade_result!=NULL){
	echo '訂單建立成功，之後自動跳轉付款頁面'.'<br><br>';
	}


echo $data.'<br>';
echo $mac.'<br><br>';
echo date("h:i:sa").'<br>';

//$test='{"ONO":"20160518101699"}';

//https://acqtest.esunbank.com.tw/ACQTrans/esuncard/txnf014s	測試
//https://acq.esunbank.com.tw/ACQTrans/esuncard/txnf014s		正式
?>

<body>
<div style = "display: inline">
<form id="from1" method="post" action="https://acqtest.esunbank.com.tw/ACQTrans/esuncard/txnf014s">            
	data<input type=`hidden name="data" value='<?php echo $data;?>'><br>
	mac<input type=`hidden name="mac" value='<?php echo $mac;?>'/><br>
	ksn<input type=`hidden name="ksn" value='<?php echo $ksn;?>'/><br>
	<button id="send" type="submit">GOGO</button>
 
 
</form>

<script>
document.getElementById("from1").submit();
</script> 

</div>

</body> 
</html>