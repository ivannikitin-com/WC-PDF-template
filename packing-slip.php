<?php 
global $wpo_wcpdf;
//пролучение полного пути
$dir = plugin_dir_path( __FILE__ );
$template_url = path();
//получаем данные клиента
$client = customer_data();

 ?>
<?php do_action( 'wpo_wcpdf_before_document', $wpo_wcpdf->export->template_type, $wpo_wcpdf->export->order ); ?>
   <header class="txt-right">
       <?php
		if( $wpo_wcpdf->get_header_logo_id() ) {
			$wpo_wcpdf->header_logo();
		} else {
			echo apply_filters( 'wpo_wcpdf_packing_slip_title', __( 'Packing Slip', 'wpo_wcpdf' ) );
		}
		?>
    </header>
    <h2 class="brd-bott" align="center">АКТ № <?php $wpo_wcpdf->invoice_number(); ?> от <?php $wpo_wcpdf->order_date(); ?></h2>
    <br>
    <section class="brd-bott">
    	<table>
    		<tr>
    			<td>Исполнитель:</td>
    			<td><h4>Индивидуальный предприниматель Никитин Иван Геннадьевич</h4></td>
    		</tr>
    		<tr>
    			<td>Заказчик:</td>
    			<td><h4><?php echo $client['name_company'];?></h4></td>
    		</tr>
    	</table>
    <br>
<?php do_action( 'wpo_wcpdf_after_document_label', $wpo_wcpdf->export->template_type, $wpo_wcpdf->export->order ); ?>

<table class="brd txt-center">
		<thead>
	        <tr>
	           <td>№</td>
	            <td>Наименование работ, услуг</td>
	            <td class="nbsp">Кол-во</td>
	            <td>Цена</td>
	            <td>Сумма</td>
	          </tr>
		</thead>
		<tbody>
		<?php 
		//вывод корзины

		$i = 1; //счётник
		$items = $wpo_wcpdf->get_order_items(); if( sizeof( $items ) > 0 ) : foreach( $items as $item_id => $item ) :
		 ?>

		<tr class="<?php echo apply_filters( 'wpo_wcpdf_item_row_class', $item_id, $wpo_wcpdf->export->template_type, $wpo_wcpdf->export->order, $item_id ); ?>">
			<td>
				<?php echo $i++; ?>
			</td>
			<td class="product">
				<?php $description_label = __( 'Description', 'wpo_wcpdf' ); // registering alternate label translation ?>
				<span class="item-name"><?php echo $item['name']; ?></span>
				<?php do_action( 'wpo_wcpdf_before_item_meta', $wpo_wcpdf->export->template_type, $item, $wpo_wcpdf->export->order  ); ?>
				<span class="item-meta"><?php echo $item['meta']; ?></span>
				<dl class="meta">
					<?php $description_label = __( 'SKU', 'wpo_wcpdf' ); // registering alternate label translation ?>
					<?php if( !empty( $item['sku'] ) ) : ?><dt class="sku"><?php _e( 'SKU:', 'wpo_wcpdf' ); ?></dt><dd class="sku"><?php echo $item['sku']; ?></dd><?php endif; ?>
					<?php if( !empty( $item['weight'] ) ) : ?><dt class="weight"><?php _e( 'Weight:', 'wpo_wcpdf' ); ?></dt><dd class="weight"><?php echo $item['weight']; ?><?php echo get_option('woocommerce_weight_unit'); ?></dd><?php endif; ?>
				</dl>
				<?php do_action( 'wpo_wcpdf_after_item_meta', $wpo_wcpdf->export->template_type, $item, $wpo_wcpdf->export->order  ); ?>
			</td>
			<td class="quantity"><?php echo $item['quantity']; ?></td>
			<td class="price"><?php echo $item['price']; ?></td>
			<td class="price"><?php echo $item['price'];?></td>
		</tr>
		<?php endforeach; endif; ?>
	</tbody>
	</table>
	<?php
		//получаем итоговую цену
	$price = $wpo_wcpdf->get_woocommerce_totals();
		//получаем нужные нам данные
	$price_sub = $price['order_total']['value'];
		//
	$price_natual = price_delimite($price_sub);
	
	?>
           <table width="100%">
           		<tr>
           			<td width="60%"></td>
           			<td width="40%">
           				<table width="100%">
           					<tr>
           					<td align="center"><h4>Итого к оплате:</h4></td>
           					<td align="right"><h4><?php echo $price['order_total']['value']; ?></h4></td>
           					</tr>
           				</table>
           		</tr>
           </table> 
           <br>
           <br>          
             <p>Общая стоимость выполненных работ, оказанных услуг: <?php echo number($price_natual['price']); ?>  </p>
             <hr>

 <div class="mrgLeft0 padTop clearfix">
<p>Заказчик не имеет претензий по срокам, качеству и объему товаров и услуг.
Счет выставлен на основании договора No 2016/24 от 21.07.2016. Тариф "Консалтинг. Месячный абонемент"</p>
</div>
<br>
<table width="100%" class="table_pack_slip">
    <tr>
        <td>Исполнитель: </td>
        <td><b>ИП Никитин Иван Геннадьевич</b></td>
        <td>Заказчик:</td>
        <td><b><?php echo $client['name_company'];?></b></td>
    </tr>
    <tr>
        <td>ИНН</td>
        <td> <span class="inn">501810901400</span> КПП__________</td>
        <td>ИНН</td>
        <td><?php echo $client['inn']; ?></td>
    </tr>
    <tr>
        <td> Адрес</td>
        <td>
            <span class="city">141067, Московская обл., г. Королев,
            мкр Болшево, ул. Комитетский Лес, д.
            10, кв. 25</span>         
        </td>
        <td>Адрес</td>
        <td><?php echo $client['address']; ?></td>
    </tr>
    <tr>
        <td>Р/с</td>
        <td>
            <span class="text">40802810102680000003</span>
        </td>
        <td>Р/c</td>
        <td><?php echo $client['account']; ?></td>
    </tr>
    <tr>
        <td>К/с</td>
        <td>
            <span class="text">40802810102680000003</span>
        </td>
        <td>К/с</td>
        <td><?php echo $client['kpp']; ?></td>
    </tr>
    <tr>
        <td>Банк</td>
        <td>
            <span class="text">АО "АЛЬФА-БАНК" г. МОСКВА</span>
        </td>
        <td>Банк</td>
        <td><?php echo $client['name_bank']; ?></td>
    </tr>
    <tr>
        <td>БИК</td>
        <td>
            <span class="text">044525593</span>
        </td>
        <td>БИК</td>
        <td><?php echo $client['blc']; ?></td>
    </tr>
    <tr>
        <td>Телефон</td>
        <td>
            <span class="text">+7 (495) 565-34-88</span>
        </td>
        <td>Телефон</td>
        <td><?php echo $client['phone']; ?></td>
    </tr>
</table>
<footer>
             <p>Счет выставлен на основании договора № 2016/24 от 21.07.2016. Тариф "Консалтинг. Месячный абонемент"</p>
         
            <table class="signing">
            	<tr>
                <td class="txt-left">Поставщик</td>
                <td>Индивидуальный предприниматель</td>
                <td style="position:relative">
					<img src="<?php echo $template_url ?>/sign.png" alt="" style="width:4cm" />
					<img src="<?php echo $template_url ?>/stamp.png" alt="" / style="width:45mm;position: absolute;top:5mm;left:0">
				</td>
				<td>Никитин И.Г. </td>
                </tr>
                <tr>
                	<td></td>
                	<td class="brd-top"><small>должность</small></td>
                	<td class="brd-top"><small>подпись</small></td>
                	<td class="brd-top"><small>расшифровка подписи</small></td>
                </tr>
            </table>
         </footer>
