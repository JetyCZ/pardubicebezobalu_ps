<?php

if (!defined('_PS_VERSION_'))
	exit;

class PrestoolsSuite extends Module
{
	private $errors = null;

	public function __construct()
	{
		// Author of the module
		$this->author = 'Prestools';	
		$this->name = 'prestoolssuite';		// Name of the module ; the same that the directory and the module ClassName
		$this->tab = 'others';	// Tab where it's the module (administration, front_office_features, ...)
		$this->version = '1.0.3'; // Current version of the module
		$this->ps_versions_compliancy['min'] = '1.5'; 	// OR $this->ps_versions_compliancy = array('min' => '1.5', 'max' => '1.6');
		$this->ps_versions_compliancy['max'] = '1.7.99.99';
		
		//	The need_instance flag indicates whether to load the module's class when displaying the "Modules" page
		//	in the back-office. If set at 0, the module will not be loaded, and therefore will spend less resources
		//	to generate the page module. If your modules needs to display a warning message in the "Modules" page,
		//	then you must set this attribute to 1.
		$this->need_instance = 0;
		$this->dependencies = array();		// Modules needed for install e.g. $this->dependencies = array('blockcart', 'blockcms');
		$this->limited_countries = array(); 	// e.g. $this->limited_countries = array('fr', 'us');
		$this->secure_key = Tools::encrypt($this->name);
			
		parent::__construct();

		// Name in the modules list
		$this->displayName = 'Prestools Suite';
		// A little description of the module
		$this->description = 'Module Prestools Suite';

		// Message show when you want to delete the module
		$this->confirmUninstall = 'Are you sure you want to delete this module ?';

		if (($this->active && Configuration::get('PRESTOOLSSUITE_DIR') == '')
			|| ($this->active && Configuration::get('PRESTOOLSSUITE_USERNAME') == '')
			|| ($this->active && Configuration::get('PRESTOOLSSUITE_PASSW') == ''))
			$this->warning = 'You have to configure your module';

		$this->errors = array();
	}

	public function install()
	{
		// Install Tabs
		$tab = new Tab();
		$tab->active = 1;
		// Need a foreach for the language
		foreach (Language::getLanguages(true) as $lang)
			$tab->name[$lang['id_lang']] = 'Prestools Suite';
		$tab->class_name = 'AdminPrestoolsSuite';
		$tab->id_parent = Tab::getIdFromClassName('AdminCatalog');
		$tab->module = $this->name;
		$tab->add();

		//Init
		Configuration::updateValue('PRESTOOLSSUITE_DIR', '');
		Configuration::updateValue('PRESTOOLSSUITE_USERNAME', '');
		Configuration::updateValue('PRESTOOLSSUITE_PASSW', '');	

		// Install Module
		// In this part, you don't need to add a hook in database, even if it's a new one.
		// The registerHook method will do it for your !
		return parent::install() && $this->registerHook('actionObjectPrestoolsSuiteDataAddAfter');
	}

	public function uninstall()
	{
		Configuration::deleteByName('PRESTOOLSSUITE_DIR');
		Configuration::deleteByName('PRESTOOLSSUITE_USERNAME');		
		Configuration::deleteByName('PRESTOOLSSUITE_PASSW');		

		// Uninstall Tabs
		$moduleTabs = Tab::getCollectionFromModule($this->name);
		if (!empty($moduleTabs)) {
			foreach ($moduleTabs as $moduleTab) {
				$moduleTab->delete();
			}
		}

		// Uninstall Module
		if (!parent::uninstall())
			return false;

		return true;
	}

	public function getContent()
	{
		$output = '<h2>'.$this->displayName.'</h2>';
		if (Tools::isSubmit('submit'.Tools::ucfirst($this->name)))
		{
			$prestools_directory = Tools::getValue('prestools_directory');
			Configuration::updateValue('PRESTOOLSSUITE_DIR', $prestools_directory);
			$prestools_username = Tools::getValue('prestools_username');
			Configuration::updateValue('PRESTOOLSSUITE_USERNAME', $prestools_username);
			$prestools_passw = Tools::getValue('prestools_passw');
			Configuration::updateValue('PRESTOOLSSUITE_PASSW', $prestools_passw);
			if (isset($this->errors) && count($this->errors))
				$output .= $this->displayError(implode('<br />', $this->errors));
			else
				$output .= $this->displayConfirmation('Settings updated');
		}
		return $output.$this->displayForm();
	}

	public function displayForm()
	{
		$this->context->smarty->assign('request_uri', Tools::safeOutput($_SERVER['REQUEST_URI']));
		$this->context->smarty->assign('path', $this->_path);
		$this->context->smarty->assign('prestools_directory', pSQL(Tools::getValue('PRESTOOLSSUITE_DIR', Configuration::get('PRESTOOLSSUITE_DIR'))));
		$this->context->smarty->assign('prestools_username', pSQL(Tools::getValue('PRESTOOLSSUITE_USERNAME', Configuration::get('PRESTOOLSSUITE_USERNAME'))));
		$this->context->smarty->assign('prestools_passw', pSQL(Tools::getValue('PRESTOOLSSUITE_PASSW', Configuration::get('PRESTOOLSSUITE_PASSW'))));
		$this->context->smarty->assign('submitName', 'submit'.Tools::ucfirst($this->name));
		$this->context->smarty->assign('errors', $this->errors);

		return $this->display(__FILE__, 'views/templates/admin/configure.tpl');
	}


	public function hookActionObjectPrestoolsSuiteDataAddAfter($params)
	{
		/* Do something here... */
		$params = $params;

		return true;
	}
}
