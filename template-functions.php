<?php

/**
 * Use this file for all your template filters and actions.
 * Requires WooCommerce PDF Invoices & Packing Slips 1.4.13 or higher
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function number($number) {
	
	// обозначаем словарь в виде статической переменной функции, чтобы 
	// при повторном использовании функции его не определять заново
	static $dic = array(
	
		// словарь необходимых чисел
		array(
			-2	=> 'две',
			-1	=> 'одна',
			1	=> 'один',
			2	=> 'два',
			3	=> 'три',
			4	=> 'четыре',
			5	=> 'пять',
			6	=> 'шесть',
			7	=> 'семь',
			8	=> 'восемь',
			9	=> 'девять',
			10	=> 'десять',
			11	=> 'одиннадцать',
			12	=> 'двенадцать',
			13	=> 'тринадцать',
			14	=> 'четырнадцать' ,
			15	=> 'пятнадцать',
			16	=> 'шестнадцать',
			17	=> 'семнадцать',
			18	=> 'восемнадцать',
			19	=> 'девятнадцать',
			20	=> 'двадцать',
			30	=> 'тридцать',
			40	=> 'сорок',
			50	=> 'пятьдесят',
			60	=> 'шестьдесят',
			70	=> 'семьдесят',
			80	=> 'восемьдесят',
			90	=> 'девяносто',
			100	=> 'сто',
			200	=> 'двести',
			300	=> 'триста',
			400	=> 'четыреста',
			500	=> 'пятьсот',
			600	=> 'шестьсот',
			700	=> 'семьсот',
			800	=> 'восемьсот',
			900	=> 'девятьсот'
		),
		
		// словарь порядков со склонениями для плюрализации
		array(
			array('рубль', 'рубля', 'рублей'),
			array('тысяча', 'тысячи', 'тысяч'),
			array('миллион', 'миллиона', 'миллионов'),
			array('миллиард', 'миллиарда', 'миллиардов'),
			array('триллион', 'триллиона', 'триллионов'),
			array('квадриллион', 'квадриллиона', 'квадриллионов'),
			// квинтиллион, секстиллион и т.д.
		),
		
		// карта плюрализации
		array(
			2, 0, 1, 1, 1, 2
		)
	);
	
	// обозначаем переменную в которую будем писать сгенерированный текст
	$string = array();
	
	// дополняем число нулями слева до количества цифр кратного трем,
	// например 1234, преобразуется в 001234
	$number = str_pad($number, ceil(strlen($number)/3)*3, 0, STR_PAD_LEFT);
	
	// разбиваем число на части из 3 цифр (порядки) и инвертируем порядок частей,
	// т.к. мы не знаем максимальный порядок числа и будем бежать снизу
	// единицы, тысячи, миллионы и т.д.
	$parts = array_reverse(str_split($number,3));
	
	// бежим по каждой части
	foreach($parts as $i=>$part) {
		
		// если часть не равна нулю, нам надо преобразовать ее в текст
		if($part>0) {
			
			// обозначаем переменную в которую будем писать составные числа для текущей части
			$digits = array();
			
			// если число треххзначное, запоминаем количество сотен
			if($part>99) {
				$digits[] = floor($part/100)*100;
			}
			
			// если последние 2 цифры не равны нулю, продолжаем искать составные числа
			// (данный блок прокомментирую при необходимости)
			if($mod1=$part%100) {
				$mod2 = $part%10;
				$flag = $i==1 && $mod1!=11 && $mod1!=12 && $mod2<3 ? -1 : 1;
				if($mod1<20 || !$mod2) {
					$digits[] = $flag*$mod1;
				} else {
					$digits[] = floor($mod1/10)*10;
					$digits[] = $flag*$mod2;
				}
			}
			
			// берем последнее составное число, для плюрализации
			$last = abs(end($digits));
			
			// преобразуем все составные числа в слова
			foreach($digits as $j=>$digit) {
				$digits[$j] = $dic[0][$digit];
			}
			
			// добавляем обозначение порядка или валюту
			$digits[] = $dic[1][$i][(($last%=100)>4 && $last<20) ? 2 : $dic[2][min($last%10,5)]];
			
			// объединяем составные числа в единый текст и добавляем в переменную, которую вернет функция
			array_unshift($string, join(' ', $digits));
		}
	}
	
	// преобразуем переменную в текст и возвращаем из функции, ура!
	return join(' ', $string);
}
//
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
                "phone" => $wpo_wcpdf->export->order->data['billing']['[phone'],
                "address" => $wpo_wcpdf->export->order->data['billing']['address_1'],
                //получаем платёжные данные покупателя
                "inn" => $wpo_wcpdf->export->order->meta_data['1']->value,
                "kpp" => $wpo_wcpdf->export->order->meta_data['2']->value,
                "orgn" => $wpo_wcpdf->export->order->meta_data['3']->value,
                "account" => $wpo_wcpdf->export->order->meta_data['4']->value, //расчетный счёт
                "name_bank" => $wpo_wcpdf->export->order->meta_data['5']->value,
                "blc" => $wpo_wcpdf->export->order->meta_data['6']->value
            );
        }