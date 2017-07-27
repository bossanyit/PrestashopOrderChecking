<?php

/*
* Copyright (C) 2014 S.C. Bossányi Tibor 
* 

*/

    class andioorderchecking extends Module {
        const INSTALL_SQL_FILE = 'install.sql';
        function __construct()
        {
            $this->name = 'andioorderchecking';
            $this->version = '2.0'; 
            $this->author = 'BT';
    
            parent::__construct();
            $this->displayName = $this->l('Andio Module: check orders');
                            
    
        }
    
        public function install()
        {
            Configuration::updateValue('ANDIO_ORDER_STATE_CHECK', 3);
            Configuration::updateValue('ANDIO_ORDER_STATE_CHECK_PERSONAL', 18);
            Configuration::updateValue('ANDIO_ORDER_STATE_WAIT_DELIVERY', 21);
            if (parent::install() == false
              //  || !this->copyAndOverride('form.tpl', 'controllers/ordercheck/helpers/form/', 'admin2009/themes/default/template/controllers/ordercheck/helpers/form/' );
                || !$this->createTab()  
                || !$this->createTable()             
               // || !$this->alterTable()
            ) { 
                return false;
            }
            return true;
        }
        
        public function uninstall()
        {             
          	$tab = new Tab(Tab::getIdFromClassName('AdminAndioOrderchecking'));
    		$tab->delete();
    		$tab = new Tab(Tab::getIdFromClassName('AdminAndioOrderdelivery'));
    		$tab->delete();        
            if (!parent::uninstall() 
    		) { 
                return false;
            }
            return true;
    		
        }
        
    	/*private function copyAndOverride($filename, $sourcedir, $destdir, $orig = false) {
    	    if ($orig) {
        	    if(!copy(_PS_ROOT_DIR_.$filename, dirname(__FILE__).'/override/orig/_'.$filename)) {
        	            Tools::displayError(sprintf('Copying the original necessery file not successfull: %s', $filename));
        				return false;
        		}
        	}
    		if(!copy(dirname(__FILE__).$sourcedir.$filename,_PS_ROOT_DIR_.$destdir.$filename)) {
    		    Tools::displayError(sprintf('Copying the necessery file not successfull: %s', $filename));
    			return false;
    		}
    	} */
    	    
        private function alterTable() {    
    	    $sql ="ALTER TABLE `"._DB_PREFIX_."pack` ADD COLUMN `id_product_item_attribute` INT(10) UNSIGNED NOT NULL DEFAULT 0 AFTER `id_product_item` ";
    	    return Db::getInstance()->Execute($sql);
    	    $sql ="ALTER TABLE `"._DB_PREFIX_."pack` ADD COLUMN `id_product_pack_attribute` INT(10) UNSIGNED NOT NULL DEFAULT 0 AFTER `id_product_item` ";
    	    return Db::getInstance()->Execute($sql);
    	}
    	
    	private function createTable() {
    	    // Create DB tables - uncomment below to use the install.sql for database manipulation
    		if (!file_exists(dirname(__FILE__).'/'.self::INSTALL_SQL_FILE))
    			return (false);
    		else if (!$sql = file_get_contents(dirname(__FILE__).'/'.self::INSTALL_SQL_FILE))
    			return (false);
    		$sql = str_replace('PREFIX_', _DB_PREFIX_, $sql);
    		$sql = preg_split("/;\s*[\r\n]+/", $sql);
    		foreach ($sql AS $query) {
    		    
    			if($query)
    				if(!Db::getInstance()->Execute(trim($query))) {				    
    					return false;	
    				}
    		}	 
    		return true;
    	}
    		
    	private function createTab() {
    	    $sql = "SELECT class_name FROM "._DB_PREFIX_."tab
    				WHERE class_name = 'AdminAndioOrderchecking'";
    		$exists = Db::getInstance()->ExecuteS($sql);
    		if(empty($exists[0]) || $exists[0]["class_name"]!="AdminAndioOrderchecking")
    		{
    			$tab = new Tab();
    			$tab->class_name = 'AdminAndioOrderchecking';
    			
    			$tab->id_parent = intval(Tab::getIdFromClassName('AdminParentOrders'));
    			
    			$tab->module = $this->name;
    			$tab->name[Language::getIdByIso('en')] = 'Checking Orders';
    			$tab->name[Language::getIdByIso('hu')] = 'Rendelések Ellenõrzése';
    			$tab->add();
    		}
    		
    		if(empty($exists[0]) || $exists[0]["class_name"]!="AdminAndioOrderdelivery")
    		{
    			$tab = new Tab();
    			$tab->class_name = 'AdminAndioOrderdelivery';
    			
    			$tab->id_parent = intval(Tab::getIdFromClassName('AdminParentOrders'));
    			
    			$tab->module = $this->name;
    			$tab->name[Language::getIdByIso('en')] = 'Delivery Orders';
    			$tab->name[Language::getIdByIso('hu')] = 'Rendelések Kiszállítása';
    			$tab->add();
    		}			
    		return true;
    	}  	
    } // End of: andioordercheck.php
?>