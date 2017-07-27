<?php

$barcode = substr($_REQUEST['barcode'], 0, -1);
$id_order = $_REQUEST['billnrcheck'];
$items = $_REQUEST['items'];
$chquantities = $_REQUEST['chquantities'];
$exception_continue = $_REQUEST['exception_continue'];
$ret=array();

$ret['code'] = 'ERR';
$ret['msg'] = '<span class="alert">Ez a termék nem szerepel ebben a csomagban!</span><input type="submit" class="button-primary" id="removebutton" value="OK, kiveszem a csomagból" />';

$order = new Order($id_order);
$products = $order->getProductsDetail();

for ($i = 0; $i < count($items); $i++){
    // a barcode exists
	if($items[$i]['code'] == $barcode){
		// mennyiség ellenõrzése és frissítése
		$quantity = $items[$i]['product_quantity'];	
    
		if ($quantity > $chquantities[$i]){
			$ret['msg'] = '';
			$ret['code'] = 'OK';
			$ret['itemid'] = 'ch_'.$items[$i]['id_product']_$items[$i]['id_product_attribute'];
			$ret['realid'] = $items[$i]['id_product']_$items[$i]['id_product_attribute'];
			$ret['itemnr'] = $chquantities[$i]+1;
			
			if ($ret['itemnr'] == $quantity)
				$ret['finish'] = '1';
			else $ret['finish'] = 0;			
			
			//break;
		}			
		else 
			$ret['msg'] = '<span class="alert">Ebbõl a termékbõl több nem szerepelhet a csomagban</span><input type="submit" class="button-primary" id="removebutton" value="OK, kiveszem a csomagból" />';

		
		if ($ret['finish'] == 0) 
			break;
	}
}

$ret['code'] = 'OK';
echo json_encode($ret);
?>