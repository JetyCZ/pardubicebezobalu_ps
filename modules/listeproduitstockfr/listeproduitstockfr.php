<?php
/**
* 2007-2016 PrestaShop.
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2016 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

 include_once _PS_MODULE_DIR_.'listeproduitstockfr/classes/ListeFr.php';

class ListeProduitStockFr extends Module
{
    public function __construct()
    {
          $this->name = 'listeproduitstockfr';
          $this->tab = 'front_office_features';
          $this->version = '1.0.0';
          $this->author = 'InnovativesLabs';
          $this->need_instance = 0;
          $this->secure_key = Tools::encrypt($this->name);
          $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
          $this->bootstrap = true;

          parent::__construct();

          $this->displayName = $this->l('Liste produits Stock FR');
          $this->description = $this->l('Affichage de la liste des produits avec desigantion.');

          $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');
    }
    public function install()
    {       

        if (!parent::install() ||
		    !$this->registerHook('header')||			
			!$this->registerHook('leftColumn')||
			!$this->registerHook('createAccountTop')||
			!$this->registerHook('createAccountForm')
		
		    ) {
              return false;
        }       

          return true;
    }

    public function uninstall()
    {
        if (!parent::uninstall()            
            ) {
              return false;
        }         
          return true;
    }
     
	
    public function hookDisplayLeftColumn($params)
    {
	   $context=Context::getContext();
        $this->context->smarty->assign(
      array(   
          'nka_link'=>$context->link->getModuleLink('listeproduitstockfr','display'),		  
		  'id_customer' => ($this->context->customer->logged ? $this->context->customer->id : false)		  	
            )
                 );
          return $this->display(__FILE__, 'listeproduitstockfr_left.tpl');
    }
	
	 public function hookDisplayRightColumn($params)
    {
         return $this->hookDisplayLeftColumn($params);
    }
	
	
	    public function hookDisplayCreateAccountForm($params)
    {
         return 'Hello Ndiaga';
    }
	
	    public function hookDisplayCreateAccountTop($params)
    {
         return 'hello Ndiaga';
    }

       

    public function getContent()
    {
              $output = null;         
        
              $output.=$this->renderForm();
			  
          return $output;
		  
    }
    //nka
    public function renderForm()
    {
            $id_lang = (int) Context::getContext()->language->id;
            
            $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Settings'),
                    'icon' => 'icon-cogs',
                ),
                'input' => array(
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Order Minimum Amount'),
                        'name' => 'SP_MINIMUM_PERMIT_KA',
                        'is_bool' => true,
                        'desc' => $this->l('Activate the option for minimum amount the supplier\'s order should be.'),
                        'values' => array(
                                    array(
                                        'id' => 'active_on',
                                        'value' => 1,
                                        'label' => $this->l('Enabled'),
                                    ),
                                    array(
                                        'id' => 'active_off',
                                        'value' => 0,
                                        'label' => $this->l('Disabled'),
                                    ),
                                ),
                        ),
                        array(
                    'type' => 'select',
                    'label' => $this->l('Select a Supplier'),
                    'name' => 'id_supplier',

                    'options' => array(
                        'query' =>'',
                        'id' => 'id_supplier',
                        'name' => 'name',

                    ),
                    'desc' => $this->l('Select the supplier you want to configure the minimum amount for.'),
                      ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Minimum amount allowed to place an Order in '),
                        'name' => 'SP_ORDER_MINIMUM_KA',
                        'class' => 'fixed-width-xs',
                        'desc' => $this->l('Minimum allowed for a French Supplier to place an order in '),
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
            ),
        );

            $helper = new HelperForm();
            $helper->show_toolbar = false;
            $helper->table = $this->table;
            $lang = new Language((int) Configuration::get('PS_LANG_DEFAULT'));
            $helper->default_form_language = $lang->id;
            $e_lang_var_one=Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG');
            $e_lang= $e_lang_var_one ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
            $helper->allow_employee_form_lang=$e_lang ;
            $this->fields_form = array();

            $helper->identifier = $this->identifier;
            $helper->submit_action = 'submitSupplierOrderLimit';
            $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).
            '&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
            $helper->token = Tools::getAdminTokenLite('AdminModules');
            $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFieldsValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

            return $helper->generateForm(array($fields_form));
    }

    public function getConfigFieldsValues()
    {
          $sp_min=Configuration::get('SP_MINIMUM_PERMIT_KA');
          return array(
            'SP_MINIMUM_PERMIT_KA'=>(bool) Tools::getValue('SP_MINIMUM_PERMIT_KA', $sp_min),
            'SP_ORDER_MINIMUM_KA' => Tools::getValue('SP_ORDER_MINIMUM_KA'),
            'id_supplier' => (int) Tools::getValue('id_supplier'),
        );
    }
	
	
	
	
     
}
