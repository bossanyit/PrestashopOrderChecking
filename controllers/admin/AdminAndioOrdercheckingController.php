<?php

include_once dirname(__FILE__).'/../../classes/OrderProductChecked.php';
/**
 * @since 1.5.0
 */
class AdminAndioOrdercheckingController extends ModuleAdminController
{
    public $id_order_state_check = 0;
    public $id_order_state_check_personal = 0;
    public $id_stock_mvt_customer_order = 0;
    public $id_order = 0;

	public function __construct()
	{
	    $this->bootstrap = true;
		$this->context = Context::getContext();
		$this->lang = false;
		
		$this->id_order_state_check = Configuration::get('ANDIO_ORDER_STATE_CHECK');
		$this->id_order_state_check_personal = Configuration::get('ANDIO_ORDER_STATE_CHECK_PERSONAL');
		$this->id_stock_mvt_customer_order = 3;
	 	
	 	$this->fields_list = array(
			'name' => array(
				'title' => $this->l('Name'),
				'width' => 50
			),
			'id_order' => array(
				'title' => $this->l('Nr'),
				'width' => 20
			),							
			'total_paid' => array(
				'title' => $this->l('Total'),
				 'width' => 20,
			),					
		    'postcode' => array(
				'title' => $this->l('Postcode'),
				'width' => 50
			),			
			'city' => array(
				'title' => $this->l('City'),
				 'width' => 20,
			),
		);

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
        $this->context->controller->addJS(__PS_BASE_URI__.'modules/andioorderchecking/js/ordercheck.js');
        $this->context->controller->addCSS(__PS_BASE_URI__.'modules/andioorderchecking/css/ordercheck.css');
    }
    /**
	 * AdminController::initContent() override
	 * @see AdminController::initContent()
	 */
	public function initContent()
	{
	    if (Tools::isSubmit('updateorders') ) {
	        $id_order = (int)Tools::getValue('id_order');
	        $this->id_order = $id_order;
	        $order = new Order($id_order);
	        $customer = $order->getCustomer();
    		            
            // defines the fields of the form to display
    		$this->fields_form[]['form'] = array(
    			'legend' => array(
    				'title' => $this->l('Checking order of the products.'),
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
    		$helper->title = $this->l('Checking order of the products');
    
    		//$helper->override_folder = '../../../../../modules/andioorderchecking/controllers/ordercheck/';
    		//$helper->override_folder = dirname(__FILE__).'/../ordercheck/helpers/form/';
    		$helper->override_folder = 'ordercheck/';
    		//echo "folder " .$helper->override_folder . " id " . $id_order;
    		// assigns our content
		    $helper->tpl_vars['id_order'] = $id_order;
		    $helper->tpl_vars['customer'] = $customer;
		    $helper->tpl_vars['products_list'] = $this->getProducts($id_order);
		    $helper->tpl_vars['pack_details'] = $this->getPackDetails($id_order);
		    $helper->base_tpl_form = 'form.tpl';
		
    		// generates the form to display
    		$content = $helper->generateForm($this->fields_form);
    
    		$this->context->smarty->assign(array(
    			'content' => $content,
    			'url_post' => self::$currentIndex.'&token='.$this->token,
    		));
    		//$this->display();
    		$this->tpl_form_vars['id_order'] = $id_order;
    
    	} else {
		// call parent initcontent to render standard form content
		    parent::initContent();    	
		}
		
	}
    protected function getPackDetails($id_order) {
        $id_lang = Context::getContext()->language->id;
        $sql = 'SELECT p.id_product as parent_id_product, packp.ean13 as product_ean13, packpa.ean13 as attribute_ean13, packp.id_product as product_id, packpa.id_product_attribute as product_attribute_id , od.product_quantity*pack.quantity as product_quantity,  if(isnull(packpa.reference), pl.name,concat( pl.name, " ", packpa.reference)) as name, p.cache_is_pack
		FROM `ps_order_detail` od
		left JOIN `'._DB_PREFIX_.'product` p ON (p.id_product = od.product_id)
		left JOIN `'._DB_PREFIX_.'pack` pack ON (pack.id_product_pack = p.id_product and pack.id_product_pack_attribute = od.product_attribute_id )	
		left JOIN `'._DB_PREFIX_.'product` packp ON (packp.id_product = pack.id_product_item)
		left join '._DB_PREFIX_.'product_lang pl on (pl.id_product = packp.id_product and pl.id_lang = '.$id_lang.')
		left JOIN `'._DB_PREFIX_.'product_attribute` packpa ON (packpa.id_product = pack.id_product_item and packpa.id_product_attribute = pack.id_product_item_attribute)											
		WHERE od.`id_order` = '.$id_order.' and p.cache_is_pack = 1';
		
		$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
		//$this->pack_products = $result;
		return $result;
		
		
    }

    protected function getProducts($id_order) {
        
		$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS(
		'SELECT p.ean13 as product_ean13, pa.ean13 as attribute_ean13, product_id, product_attribute_id, product_quantity, product_name, p.cache_is_pack
		FROM `'._DB_PREFIX_.'order_detail` od
		LEFT JOIN `'._DB_PREFIX_.'product` p ON (p.id_product = od.product_id)
		LEFT JOIN `'._DB_PREFIX_.'product_attribute` pa ON (pa.id_product = od.product_id and pa.id_product_attribute = od.product_attribute_id)						
		WHERE od.`id_order` =  '.$id_order);
		
		//$this->products = $result;
		return $result;
    }

	/**
	 * AdminController::renderList() override
	 * @see AdminController::renderList()
	 */
	public function renderList()
	{
		$this->displayInformation($this->l('This interface allows you to check the deliverable packets.').'<br />');
		
		//$this->addRowAction('view');		
		$this->addRowAction('edit');	
		$this->addRowAction('CheckOrder');	
	    $this->table = 'orders';
        $this->identifier = 'id_order';
		
		// no link on list rows
		$this->list_no_link = true;

		// inits toolbar
		$this->toolbar_btn = array();

       //$sql = 
       $this->_select = 'concat(b.lastname, " ", b.firstname) as name, a.id_order as ID, a.total_paid, c.postcode, c.city';
       $this->_join = ' LEFT JOIN `'._DB_PREFIX_.'customer` b ON (b.id_customer = a.id_customer)';
	   $this->_join .= ' LEFT JOIN `'._DB_PREFIX_.'address` c ON (c.id_customer = b.id_customer) and id_address_invoice = c.id_address';	
	   $this->_where = ' and a.current_state = ' .$this->id_order_state_check . ' or a.current_state = ' .$this->id_order_state_check_personal;
	   $this->_orderBy = 'id_order';

       $this->show_toolbar = false;
       return parent::renderList();
	}
			
	/**
	 * AdminController::postProcess() override
	 * @see AdminController::postProcess()
	 */
	public function postProcess()
	{
	    if ( Tools::getValue('submit')==1) {
	        $this->id_order = Tools::getValue('billnrcheck');
	        OrderStateProcess::getNextState($this->id_order, $this, false);
	        $this->saveCheckedProducts($this->id_order);
	    }
	    parent::postProcess();
    }
    
    protected function saveCheckedProducts($id_order) {
        $products = $this->getProducts($id_order);
        foreach ($products as $product) {
            if ($product['cache_is_pack'] == 0) {
               $product_check = new OrderProductChecked();
               $product_check->id_order = $id_order;
               $product_check->id_product = $product['product_id'];
               $product_check->id_product_attribute = $product['product_attribute_id'];
               $product_check->quantity = $product['product_quantity'];
               $checked_quantity_name = 'checked_'.$product['product_id'].'_'.$product['product_attribute_id'];          
               $product_check->checked = Tools::getValue($checked_quantity_name);
               $product_check->add();
            }
        }
        
        $pack_products = $this->getPackDetails($id_order);
        foreach ($pack_products as $product) {
               $product_check = new OrderProductChecked();
               $product_check->id_order = $id_order;
               $product_check->id_product = $product['product_id'];
               $product_check->id_product_attribute = $product['product_attribute_id'];
               $product_check->quantity = $product['product_quantity'];
               $checked_quantity_name = 'checked_'.$product['product_id'].'_'.$product['product_attribute_id'];             
               $product_check->checked = Tools::getValue($checked_quantity_name);
               $product_check->add();
        }
    }
}
