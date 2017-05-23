<?php

/**
 * Use this file for all your template filters and actions.
 * Requires WooCommerce PDF Invoices & Packing Slips 1.4.13 or higher
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function num2str($num) {
	$nul='ноль';
	$ten=array(
		array('','один','два','три','четыре','пять','шесть','семь', 'восемь','девять'),
		array('','одна','две','три','четыре','пять','шесть','семь', 'восемь','девять'),
	);
	$a20=array('десять','одиннадцать','двенадцать','тринадцать','четырнадцать' ,'пятнадцать','шестнадцать','семнадцать','восемнадцать','девятнадцать');
	$tens=array(2=>'двадцать','тридцать','сорок','пятьдесят','шестьдесят','семьдесят' ,'восемьдесят','девяносто');
	$hundred=array('','сто','двести','триста','четыреста','пятьсот','шестьсот', 'семьсот','восемьсот','девятьсот');
	$unit=array( // Units
		array('копейка' ,'копейки' ,'копеек',	 1),
		array('рубль'   ,'рубля'   ,'рублей'    ,0),
		array('тысяча'  ,'тысячи'  ,'тысяч'     ,1),
		array('миллион' ,'миллиона','миллионов' ,0),
		array('миллиард','милиарда','миллиардов',0),
	);
	//
	list($rub,$kop) = explode('.',sprintf("%015.2f", floatval($num)));
	$out = array();
	if (intval($rub)>0) {
		foreach(str_split($rub,3) as $uk=>$v) { // by 3 symbols
			if (!intval($v)) continue;
			$uk = sizeof($unit)-$uk-1; // unit key
			$gender = $unit[$uk][3];
			list($i1,$i2,$i3) = array_map('intval',str_split($v,1));
			// mega-logic
			$out[] = $hundred[$i1]; # 1xx-9xx
			if ($i2>1) $out[]= $tens[$i2].' '.$ten[$gender][$i3]; # 20-99
			else $out[]= $i2>0 ? $a20[$i3] : $ten[$gender][$i3]; # 10-19 | 1-9
			// units without rub & kop
			if ($uk>1) $out[]= morph($v,$unit[$uk][0],$unit[$uk][1],$unit[$uk][2]);
		} //foreach
	}
	else $out[] = $nul;
	$out[] = morph(intval($rub), $unit[1][0],$unit[1][1],$unit[1][2]); // rub
	$out[] = $kop.' '.morph($kop,$unit[0][0],$unit[0][1],$unit[0][2]); // kop
	return trim(preg_replace('/ {2,}/', ' ', join(' ',$out)));
}

/**
 * Склоняем словоформу
 * @ author runcore
 */
function morph($n, $f1, $f2, $f5) {
	$n = abs(intval($n)) % 100;
	if ($n>10 && $n<20) return $f5;
	$n = $n % 10;
	if ($n>1 && $n<5) return $f2;
	if ($n==1) return $f1;
	return $f5;
}

//разделитель суммы
function price_delimite($price_wc){
    $one_price = explode(".", $price_wc);
    $pr = $one_price[0];
    $cent = explode(" ", $pr[1]);
    $cent_pr = $cent[0];
    $mass = array(
        "price" => $pr,
        "cent" => $cent_pr
    );
    return $mass;
}	
        
function path(){
    return plugin_dir_path( __FILE__ );
}
        
//возвращаем данные клиента
function customer_data(){
     global $wpo_wcpdf;
     return array(
        "name_company" => $wpo_wcpdf->export->order->data['billing']['company'],  
        "phone" => $wpo_wcpdf->export->order->data['billing']['phone'],
        "address" => $wpo_wcpdf->export->order->data['billing']['address_1'],
        //получаем платёжные данные покупателя
        "inn" => get_order_meta('_billing_inn'),
        "kpp" => get_order_meta('_billing_kpp'),
        "orgn" => get_order_meta('_billing_ogrn'),
        "total_order" => get_order_meta('total'),
        "account" => get_order_meta('_billing_account'), //расчетный счёт
        "name_bank" => get_order_meta('_billing_bank'),
        "blc" => get_order_meta('_billing_bic')                
    );      
}


function get_order_meta( $key )
{
	global $wpo_wcpdf;
	$meta = $wpo_wcpdf->export->order->meta_data;
	foreach ($meta as $obj ) {
		if ( $obj->key == $key ) 
			return $obj->value;
	}
	return false;	
}

