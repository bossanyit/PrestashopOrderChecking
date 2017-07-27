{extends file="helpers/form/form.tpl"}
{block name="other_input"}
{if isset($id_order) }
<div class="margin-form">
    <form action="#" method="post" id="check_form" >
        <div id='orderinfo'>
            
    		<p>{l s='Name: '  }<strong> {$customer->lastname} {$customer->firstname} </strong></p>
    		<p>{l s='Order: '  } <strong>{$id_order}</strong></p>
        </div>
        <div id='checkrow'>
            <legend>
    			<img alt="Order Check" src="../img/admin/edit.gif">
    			{l s='Read the product barcode here'  }
    		</legend>
    		<input type="text" size="50" id="barcodeinput" />	
    		<input type='hidden' id='billnrcheck' name='billnrcheck' value='{$id_order}' />    		
    		<p id='answer'></p>
    	</div>
		
		<p>&nbsp;</p>

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
								<th style="width: 100px">{l s='Product'  }</th>
								<th style="width: 50px">{l s='Quantity'  }</th>
								<th style="width: 50px">{l s='Checked quantity'  }</th>	
								<th style="width: 50px">{l s='EAN13'  }</th>								
							</tr>
						</thead>
						<tbody>
							{foreach $products_list AS $product}
							    {if $product.cache_is_pack}
    							    <tr class='product_item' style="height:34px;" id="{$product.product_id}_{$product.product_attribute_id}">
    									
    									<td>									    
    										<input type="text" name="name_{$product.product_id}_{$product.product_attribute_id}" value="{$product.product_name}" readonly="readonly" size="80"/>																			
    									</td>
    									<td>
    									</td>
    									<td>									    
    									</td>
    									<td>										
    										{l s='PACK'  }
    									</td>		  
    								</tr>
    								{foreach $pack_details AS $pproduct}
    								   
    								    
    								    {if $pproduct.parent_id_product == $product.product_id} 
    								     <tr class='pproduct_item' style="height:34px;" id="{$pproduct.product_id}_{$pproduct.product_attribute_id}">
        									<td style="padding-left: 35px">
        									    <input type="hidden" name="id_product_{$pproduct.product_id}_{$pproduct.product_attribute_id}" class="id_product" value="{$pproduct.product_id}" />
        									    <input type="hidden" name="id_product_attribute_{$pproduct.product_id}_{$pproduct.product_attribute_id}" class="id_product_attribute" value="{$pproduct.product_attribute_id}" />
        									    <input type="hidden" name="product_quantity" class="product_quantity" value="{$pproduct.product_quantity}" />
        									    
        										<input type="text" name="name_{$pproduct.product_id}_{$pproduct.product_attribute_id}" value="{$pproduct.name} {$pproduct.product_attribute_id}" readonly="readonly" size="80"/>																			
        									</td>
        									<td>
        									    <input type="text" id="quantity_{$pproduct.product_id}_{$pproduct.product_attribute_id}" value="{$pproduct.product_quantity}" readonly="readonly" />																			
        									</td>
        									<td>										
        										<input type="text" id="checked_{$pproduct.product_id}_{$pproduct.product_attribute_id}" name="checked_{$pproduct.product_id}_{$pproduct.product_attribute_id}" class="checked_{$pproduct.product_id}_{$pproduct.product_attribute_id}" value="0" readonly="readonly"/>
        									</td>		
        									<td>
        									    {if $pproduct.product_attribute_id>0}
        									        <input type="text" name="code" class="code" value="{$pproduct.attribute_ean13}" readonly="readonly"/>
        									    {else}
        									        <input type="text" name="code" class="code" value="{$pproduct.product_ean13}" readonly="readonly"/>        									        
        									    {/if}							
        									</td>    
        								</tr>
        								{/if}
        								
    								{/foreach}
							    {else}
								<tr class='product_item' style="height:34px;" id="{$product.product_id}_{$product.product_attribute_id}">
									<td>
									    <input type="hidden" name="id_product_{$product.product_id}_{$product.product_attribute_id}" class="id_product" value="{$product.product_id}" />
									    <input type="hidden" name="id_product_attribute_{$product.product_id}_{$product.product_attribute_id}" class="id_product_attribute" value="{$product.product_attribute_id}" />
									    <input type="hidden" name="product_quantity" class="product_quantity" value="{$product.product_quantity}" />
									    
										<input type="text" name="name_{$product.product_id}_{$product.product_attribute_id}" value="{$product.product_name}" readonly="readonly" size="80"/>																			
									</td>
									<td>
									    <input type="text" id="quantity_{$product.product_id}_{$product.product_attribute_id}" name="quantity_{$product.product_id}_{$product.product_attribute_id}" value="{$product.product_quantity}" readonly="readonly" />																			
									</td>
									<td>										
										<input type="text" id="checked_{$product.product_id}_{$product.product_attribute_id}" name="checked_{$product.product_id}_{$product.product_attribute_id}" class="checked_{$product.product_id}_{$product.product_attribute_id}" value="0" readonly="readonly"/>
									</td>		
									<td>
									    {if $product.product_attribute_id==0}
									        <input type="text" name="code" class="code" value="{$product.product_ean13}" readonly="readonly"/>
									    {else}
									        <input type="text" name="code" class="code" value="{$product.attribute_ean13}" readonly="readonly"/>
									    {/if}							
									</td>    
								</tr>
								{/if}
							{/foreach}
						</tbody>
					</table>
				</td>
			</tr>
		</table>
		<div>
		    <input type='submit' class='btn btn-default' id='finishpack' value='{l s='Items are OK, closing'  }' />
		</div>
	</form>
    <br style='clear: both'; />
</div>


{/if}
{/block}