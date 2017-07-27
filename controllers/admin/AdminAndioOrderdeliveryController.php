<?php

//include_once dirname(__FILE__).'/../../classes/OrderProductChecked.php';
/**
 * @since 1.5.0
 */
class AdminAndioOrderdeliveryController extends ModuleAdminController
{
    public $id_order_state_waiting_for_delivery = 0;
    public $id_order_state_check = 0;
    public $id_order_state_check_personal = 0;    
    public $id_order = 0;

	public function __construct()
	{
	    $this->bootstrap = true;
		$this->context = Context::getContext();
		$this->lang = false;
		
		$this->id_order_state_waiting_for_delivery = Configuration::get('ANDIO_ORDER_STATE_WAIT_DELIVERY');
		$this->id_order_state_check = Configuration::get('ANDIO_ORDER_STATE_CHECK');
		$this->id_order_state_check_personal = Configuration::get('ANDIO_ORDER_STATE_CHECK_PERSONAL');		
	 	
		parent::__construct();
	}

	/**
	 * AdminController::init() override
	 * @see AdminController::init()
	 */
	public function init()
	{
		parent::init();		
	}
    
    public function setMedia() {
        parent::setMedia();
        $this->context->controller->addJS(__PS_BASE_URI__.'modules/andioorderchecking/js/orderdelivery.js');
        $this->context->controller->addCSS(__PS_BASE_URI__.'modules/andioorderchecking/css/ordercheck.css');
    }
    /**
	 * AdminController::initContent() override
	 * @see AdminController::initContent()
	 */
	public function initContent()
	{
	    //if (Tools::isSubmit('updateorders') ) {
    		            
            // defines the fields of the form to display
    		$this->fields_form[]['form'] = array(
    			'legend' => array(
    				'title' => $this->l('Delivery of the orders'),
    				'image' => '../img/admin/cms.gif'
    			)
    		);
    		
    		// loads languages
    		$this->getlanguages();

    		// sets up the helper
    		$helper = new HelperForm();
    		$helper->submit_action = 'submit';
    		$helper->currentIndex = self::$currentIndex;
    		$helper->toolbar_btn = $this->toolbar_btn;
    		$helper->toolbar_scroll = false;
    		$helper->token = $this->token;
    		$helper->id = null; // no display standard hidden field in the form
    		$helper->languages = $this->_languages;
    		$helper->default_form_language = $this->default_form_language;
    		$helper->allow_employee_form_lang = $this->allow_employee_form_lang;
    		$helper->title = $this->l('Delivery of orders');

    		$helper->override_folder = 'orderdelivery/';
    		// assigns our content
    		$helper->tpl_vars['orders'] = $this->getOrders();

		    $helper->base_tpl_form = 'form.tpl';
		
    		// generates the form to display
    		$content = $helper->generateForm($this->fields_form);
    
    		$this->context->smarty->assign(array(
    			'content' => $content,
    			'url_post' => self::$currentIndex.'&token='.$this->token,
    		));
    		//parent::initContent();    	
    		//$this->display();
    		//$this->tpl_form_vars['id_order'] = $id_order;
    
    //	} else {
		// call parent initcontent to render standard form content
	//	    parent::initContent();    	
	//	}
		
	}
	
	protected function getOrders() {
	    $sql = ' select concat(b.lastname, " ", b.firstname) as name, a.id_order as id_order, a.total_paid, concat( c.postcode, " ", c.city) as city, if(a.packing_date="0000-00-00 00:00:00"," ", packing_date) as packing_date
	                 FROM `'._DB_PREFIX_.'orders` a
                     LEFT JOIN `'._DB_PREFIX_.'customer` b ON (b.id_customer = a.id_customer)
	                 LEFT JOIN `'._DB_PREFIX_.'address` c ON (c.id_customer = b.id_customer) and id_address_invoice = c.id_address
	                 where a.current_state = 13
	                 order By id_order';
	    return Db::getInstance()->ExecuteS($sql);
	}


	
			
	/**
	 * AdminController::postProcess() override
	 * @see AdminController::postProcess()
	 */
	public function postProcess()
	{
	    if ( Tools::getValue('submit')==1) {
	        $orders = $this->getCheckedOrders();
	         Logger::addLog('suitable orders:  ' . count($orders));
	        if (count($orders) > 0) {
    	        foreach($orders as $order) {
    	            $checked = Tools::getValue('deliverit_'.$order['id_order']);
    	            Logger::addLog('Order id ' . $order['id_order'] . ' is about to check...' . $checked );
    	            if ($checked == 'X') {
    	                Logger::addLog('Order id ' . $order['id_order'] . ' changing state to waiting to delivery' );
    	                OrderStateProcess::getNextState($order['id_order'], $this, false);
    	            }
    	        }
    	    } else {
    	       
    	    }
	    }
	    parent::postProcess();
    }
    
    protected function getCheckedOrders() {
        $sql = "select id_order, total_paid
                    FROM `"._DB_PREFIX_."orders` a 
                    where a.current_state = 13
                    order By id_order";
        
        return Db::getInstance()->ExecuteS($sql);                    
    }
    
    
}
