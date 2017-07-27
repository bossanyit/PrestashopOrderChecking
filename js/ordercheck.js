// js class
function product_item (id_product, id_product_attribute, code, quantity) {
    this.id_product = id_product;
    this.id_product_attribute = id_product_attribute;
    this.code = code;
    this.quantity = quantity;
}

function ordercheck (items, barcode, billnrchk, check_quantities, msg) {
    var i;
    var itemnr;
    var finish;
    var check_itemid;
    var realid;
    msg = '<span class="alert">Ez a termék nem szerepel ebben a csomagban!</span><input type="submit" class="button-primary" id="removebutton" value="OK, kiveszem a csomagból" />';
    var code = 'ERR';
    for (i = 0; i < items.length;i++) {
        if (barcode.substring(0, 12) == items[i].code) {
            var quantity = parseInt(items[i].quantity);	
            if (quantity > parseInt(check_quantities[i])) {
                msg = '';
                code = 'OK';
                itemnr = parseInt(check_quantities[i]) + 1;
                check_quantities[i] = parseInt(check_quantities[i]) + 1;
                check_itemid = 'checked_' + items[i].id_product + '_' + items[i].id_product_attribute;
	            realid = + items[i].id_product + '_' + items[i].id_product_attribute;
                if (itemnr == quantity)
		            finish = 1;
		        else 
		            finish = 0;
            } else {
    	        msg = '<span class="alert">Ebből a termékből több nem szerepelhet a csomagban</span><input type="submit" class="button-primary" id="removebutton" value="OK, kiveszem a csomagból" />';
    	    }
            if (finish == 0) {
    	        break;
    	    }
        }
    }
    var obj = {
        code : code,
        msg : msg,
        finish : finish,
        itemnr : itemnr,
        check_itemid : check_itemid,
        realid : realid
    }
    return obj;
   
}
            
$(function() {
        var items = [];

        //jQuery("#itemlist > tbody  > tr ").each(
        jQuery("tr.product_item").each(
            function() {
                item = new product_item(0,0,'',0); 
                item.id_product = $(this).find("input.id_product").val();
                item.id_product_attribute = $(this).find("input.id_product_attribute").val();
                item.code = $(this).find("input.code").val();
                item.quantity = $(this).find("input.product_quantity").val();
                items.push(item);
            }
        );
        jQuery("tr.pproduct_item").each(
            function() {
                item = new product_item(0,0,'',0); 
                item.id_product = $(this).find("input.id_product").val();
                item.id_product_attribute = $(this).find("input.id_product_attribute").val();
                item.code = $(this).find("input.code").val();
                item.quantity = $(this).find("input.product_quantity").val();
                items.push(item);
            }
        );
        
        jQuery("#barcodeinput").val('');
		jQuery("#barcodeinput").focus();
		
    	jQuery(document).on("click", "#removebutton", function(){ 
			jQuery("#answer").html('');
			jQuery("#barcodeinput").val('');
			jQuery("#barcodeinput").focus();
		});
			
        jQuery(document).on("keyup", "#barcodeinput", function(){ 
				var datavalue = jQuery(this).val();	

			    if (datavalue.length == 13){			    				      
			    	var billnrchk = jQuery('#billnrcheck').val();
					//var itemnr = jQuery('#itemnr').val();					
					var check_quantities = [];
					var real_quantities = [];

					for (var i = 0; i < items.length; i++) {							
						akt_item_checked = "checked_"+items[i].id_product+"_"+items[i].id_product_attribute;
						akt_item_quantity = "quantity_"+items[i].id_product+"_"+items[i].id_product_attribute;
						check_quantities.push(jQuery('#' + akt_item_checked).val() );
						real_quantities.push(jQuery('#' + akt_item_quantity).val() );			 			 
					} 

					jQuery("#answer").html('<img src="../img/loader.gif" />');					

                    msg = '';
                    ret = ordercheck(items, datavalue, billnrchk, check_quantities, msg );

					if (ret.code == 'ERR') {										
						jQuery("#barcodeinput").val('');
						jQuery("#barcodeinput").focus();
						jQuery("#answer").html(ret.msg);
						jQuery('embed').remove();
						//jQuery('#checkrow').append('<embed src="../modules/andioorderchecking/sounds/error.wav" autostart="true" hidden="true" loop="false" width="200px" height="50px"></embed>');
						jQuery('#checkrow').append('<audio src="../modules/andioorderchecking/sounds/error.wav" autoplay="true" ></audio>');
						//jQuery('#checkrow').append('<img alt="Order Check" src="../img/admin/edit.gif">');
					}

					if (ret.code == 'OK') {											
						jQuery("#"+ret.check_itemid).val(ret.itemnr);
						jQuery("#answer").html('');	
						jQuery("#barcodeinput").val('');
						jQuery("#barcodeinput").focus();
						jQuery('embed').remove();
						
							
						if (ret.finish ==1){										
							jQuery("tr#"+ret.realid).removeClass('waitingrow').addClass('fullrow');
						}	
						if (ret.finish==0){										
							jQuery("tr#"+ret.realid).addClass('waitingrow');											
						}

						// finishing sound
						var finishing = 1;
						for (var i = 0; i < items.length; i++) {					
			 				if (check_quantities[i] != real_quantities[i]) {
			 				    finishing = 0;
			 				    break;
			 				}
						}

						

						if (finishing == 1){
							jQuery('embed').remove();
							//jQuery('#checkrow').append('<embed src="../modules/andioorderchecking/sounds/finish.wav" autostart="true" hidden="true" loop="false"></embed>');
							jQuery('#checkrow').append('<audio src="../modules/andioorderchecking/sounds/finish.wav" autoplay="true" ></audio>');
						} else {
						    //jQuery('#checkrow').append('<embed src="../modules/andioorderchecking/sounds/right.wav" autostart="true" hidden="true" loop="false"></embed>');		
						    jQuery('#checkrow').append('<audio src="../modules/andioorderchecking/sounds/right.wav" autoplay="true" ></audio>');
						}
									
					} 		

			      }
			      else jQuery("#answer").html('');	
			});
});