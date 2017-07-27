// js class
function order_item (id_order) {
    this.id_order = id_order;
}

            
$(function() {
        var items = [];
        jQuery("tr.order_item").each(
            function() {
                item = new order_item(0); 
                item.id_order = $(this).find("input.id_order").val();
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
			    	var ordernrchk = jQuery('#barcodeinput').val().substring(0,5);
                    var can_be_packed = today() >= $('#packing_date_'+ordernrchk).val();
                    if ( $('#id_'+ordernrchk).length!=0 && can_be_packed) {                        
    					jQuery("#answer").html('<img src="../img/loader.gif" />');					
    
                        msg = '';
                        jQuery('tr#tr_'+ordernrchk).addClass('fullrow');
                        jQuery('#deliverit_'+ordernrchk).val('X');
                        jQuery('embed').remove();    					
    					jQuery('#orderrow').append('<audio src="../modules/andioorderchecking/sounds/right.wav" autoplay="true" ></audio>');
            			jQuery("#barcodeinput").val('');
            			jQuery("#barcodeinput").focus();
    					jQuery("#answer").html('');
    				}  else {
    				    jQuery('#orderrow').append('<audio src="../modules/andioorderchecking/sounds/error.wav" autoplay="true" ></audio>');
    				    jQuery("#barcodeinput").val('');
            			jQuery("#barcodeinput").focus();
    					jQuery("#answer").html('<span class="alert">Kiszallitas idopontja: ' + $('#packing_date_'+ordernrchk).val()+'</span>');
    				}          
				} 		
                else jQuery("#answer").html('');	
	        //}
		
	    });
	    
	    function today() {
	        var today = new Date();
            var dd = today.getDate();
            var mm = today.getMonth()+1; //January is 0!
        
            var yyyy = today.getFullYear();
            if(dd<10){
                dd='0'+dd
            } 
            if(mm<10){
                mm='0'+mm
            } 
            
            var sDay = yyyy + '-' + mm + '-' + dd + '00:00:01';
            return sDay;
	    }
});