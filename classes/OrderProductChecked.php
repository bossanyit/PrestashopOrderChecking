<?php
	
class OrderProductChecked extends ObjectModel {
  public $id;
  public $id_product;
  public $id_product_attribute;
  public $id_order;
  public $quantity;
  public $checked;
 
  
  /**
	 * @see ObjectModel::$definition
	 */
	public static $definition = array(
		'table' => 'order_product_checked',
		'primary' => 'id_checked',
		'multilang' => false,
		'fields' => array(			
			'id_product' =>		    array('type' => self::TYPE_INT, 'validate' => 'isInt'),
			'id_product_attribute' => array('type' => self::TYPE_INT, 'validate' => 'isInt'),
			'id_order' =>	    	array('type' => self::TYPE_INT, 'validate' => 'isInt'),
			'quantity' =>		    array('type' => self::TYPE_INT, 'validate' => 'isInt'),
			'checked'  =>		    array('type' => self::TYPE_INT, 'validate' => 'isInt'),
			
		),
	);
 
        
}
?>