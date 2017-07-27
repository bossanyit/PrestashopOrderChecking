{extends file="helpers/form/form.tpl"}
{block name="other_input"}

<div class="margin-form">
    <form action="#" method="post" id="delivery_form" >

        <div id='orderrow'>
            <legend>
    			<img alt="Order Delivery" src="../img/admin/edit.gif">
    			{l s='Read the order barcode here'  mod='orderchecking' }
    		</legend>
    		<input type="text" size="50" id="barcodeinput" />	
    		
    		<p id='answer'></p>
    	</div>
		
		<p>&nbsp;</p>
		<p style="font-size:12px;color:#c200000">{l s='Count of delivering orders: '  mod='orderchecking' }<input type="text" size="3" value="0" id="count_orders" /></p>
		<table class="table_grid" id="itemlist">
			<tr>
				<td>
					<table
					id="products_in_supply_order"
					class="table"
					cellpadding="0" cellspacing="0"
					style="width: 100%; margin-bottom:10px;"
					>
						<thead>
							<tr class="nodrag nodrop">
								<th style="width: 10%">{l s='Order Nr'  mod='orderchecking'}</th>
								<th style="width: 35%">{l s='Name'  mod='orderchecking'}</th>
								<th style="width: 15%">{l s='City'  mod='orderchecking'}</th>
								<th style="width: 20%">{l s='Total'  mod='orderchecking'}</th>						
								<th style="width: 20%">{l s='Delivery date'  mod='orderchecking'}</th>	
								<th style="width: 20%">{l s='Deliver!'  mod='orderchecking'}</th>
							</tr>
						</thead>
						<tbody>
							{foreach $orders AS $order}
							    <tr class='order_item' style="height:12px;" id="tr_{$order.id_order}">
        						    <td><input type="text" id="id_{$order.id_order}" name="id_{$order.id_order}" value="{$order.id_order}" readonly="readonly" size="7"/></td>
        						    <td>{$order.name}</td>
        						    <td>{$order.city}</td>
        						    <td>{$order.total_paid}</td>
        						    <td><input type="text" id="packing_date_{$order.id_order}" name="packing_date_{$order.id_order}" value="{$order.packing_date}" readonly="readonly" size="15"/></td>
        						    <td><input type="text" id="deliverit_{$order.id_order}" name="deliverit_{$order.id_order}" value="-" readonly="readonly" size="1"/></td>
        						</tr>    
							{/foreach}
						</tbody>
					</table>
				</td>
			</tr>
		</table>
		<div>
		    <input type='submit' class='button-primary' id='finishdelivery' value='{l s='Send'  }' />
		</div>
	</form>
    <br style='clear: both'; />
</div>



{/block}