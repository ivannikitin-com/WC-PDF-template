<?php
global $wpo_wcpdf;
do_action( 'wpo_wcpdf_before_document', $wpo_wcpdf->export->template_type, $wpo_wcpdf->export->order); 

//ini_set('error_reporting', E_ALL);
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
// URL к папке шаблона
$template_url = path();

$client = customer_data();

?>
	<?php do_action( 'wpo_wcpdf_before_order_data', $wpo_wcpdf->export->template_type, $wpo_wcpdf->export->order ); ?>
        <header>
        <div class="shop-name nbsp"><h3>Индивидуальный предприниматель Никитин Иван Геннадьевич</h3></div>
            <table class="head container">
                <tr>
                    <td class="shop-info">
                        <div class="shop-address">
                            <div>141067, Москвоская область, г. Королев, ул. Комитетский лес, 10/25</div>
                            <div>Телефон: +7 (495) 565-34-88</div>
                            <div>E-mail: info@ivannikitin.com</div>
                            <div>Сайт: https://ivannikitin.com</div>
                        </div>
                    </td>
                    <td class="header">
                    	<?php
                    	//лого
			if( $wpo_wcpdf->get_header_logo_id() ) {
                            $wpo_wcpdf->header_logo();
			} else {
                            echo apply_filters( 'wpo_wcpdf_invoice_title', __( 'Invoice', 'wpo_wcpdf' ) );
			}			
			?>
                    </td>
                </tr>
            </table>		
        </header>
           <section>
            <table class="brd">
            <caption>Образец заполнения платежного поручения</caption>
            	<tbody>
                    <tr>
                        <td colspan="4" class="brd0">АО «Альфа-Банк» Москва</td>
                        <td>БИК</td>
                        <td style="width:25%">044525593</td>
                    </tr>
                    <tr>
                        <td colspan="4" class="brd0"></td>
                        <td class="nbsp brdBot0">Сч. №</td>
                        <td style="width:25%">30101810200000000593</td>
                    </tr>
                    <tr>
                        <td colspan="4" class="brd0">Банк получателя</td>
                        <td class="brdTop0"></td>
                        <td></td>
                    </tr>
                	<tr>
                    	<td>ИНН</td>
                        <td style="width:25%">501810901400</td>
                        <td>КПП</td>
                        <td style="width:25%"></td>
                        <td class="brdBot0">Сч. №</td>
                        <td>40802810102680000003</td>
                    </tr>
                	<tr>
                    	<td colspan="4" class="brd0">Индивидуальный предприниматель Никитин Иван Геннадьевич</td>
                        <td class="brdTop0 brdBot0"></td>
                        <td class="brdBot0"></td>
                    </tr>
                	<tr>
                    	<td colspan="4" class="brdTop0">Получатель</td>
                        <td class="brd0"></td>
                        <td class="brdTop0"></td>
                    </tr>
                </tbody>
            </table>
        </section>
         <section class="brd-bott">
        	<h1>Счёт на оплату № <?php $wpo_wcpdf->invoice_number(); ?> от <?php $wpo_wcpdf->order_date(); ?></h1>
            <table>
            <tbody>
            	<tr>
                    <td>Поставщик:</td><td>Индивидуальный предприниматель Никитин Иван Геннадьевич</td>
                </tr> 
                    <tr><td>Покупатель:</td><td><?php echo $client['name_company'];?></td></tr>
            </tbody>
            </table>
        </section>
	<table class="brd txt-center">
		<thead>
	        <tr>
	           <td>№</td>
	            <td>Товары (работы, услуги)</td>
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
        <pre>
           <table width="100%">
           	<tr>
                    <td width="60%"></td>
                    <td width="40%">
                            <table width="100%">
                                <tr>
                                    <td align="center"><h4>Итого к оплате:</h4></td>
                                    <td align="right"><h4><?php echo $client['total_order']; ?></h4></td>
                                </tr>
                            </table>
           		</tr>
           </table>           
             <p>Всего к оплате: <?php echo num2str($client['total_order']); ?>  </p>
             <hr>
              <footer>
            
         
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
	</body>
