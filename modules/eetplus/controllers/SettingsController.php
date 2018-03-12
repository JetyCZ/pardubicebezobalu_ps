<?php
/**
 * eetplus module for Prestashop
 *
 * PHP version 5
 *
 * LICENSE: The buyer can free use/edit/modify this software in anyway
 * The buyer is NOT allowed to redistribute this module in anyway or resell it
 * or redistribute it to third party
 *
 * @package    eetplus
 * @author    Vaclav Mach <info@prestahost.cz>
 * @copyright 2017 Vaclav Mach
 * @license   EULA
 * @link       http://www.prestahost.cz
 */
class SettingsController extends EetplusController
{
    protected $settings = array();
    
    public function __construct($instance)
    {
        $this->instance = $instance;
    }
    
    public function postProcess()
    {
        $keys = array_keys(Tools::getValue('cmd_Settings'));
        if (is_array($keys)) {
            $key = $keys[0];
            switch ($key) {
                case 0:
                    Configuration::updateValue('EETPLUS_SANDBOX', Tools::getValue('EETPLUS_SANDBOX'));
                    Configuration::updateValue('EETPLUS_IDPROVOZ', Tools::getValue('EETPLUS_IDPROVOZ'));
                    Configuration::updateValue('EETPLUS_IDPOKL', Tools::getValue('EETPLUS_IDPOKL'));
                    Configuration::updateValue('EETPLUS_DICPOPL', Tools::getValue('EETPLUS_DICPOPL'));
                    Configuration::updateValue('EETPLUS_IC', Tools::getValue('EETPLUS_IC'));
                    Configuration::updateValue('EETPLUS_TEXT', Tools::getValue('EETPLUS_TEXT'));
                    Configuration::updateValue('EETPLUS_TEXTCOL', Tools::getValue('EETPLUS_TEXTCOL')); 
                    
                    
                    break;
                case 1: {
                    if (isset($_FILES['EETPLUS_CERT']['name'])) {
                        if ($_FILES['EETPLUS_CERT']['error'] == 0) {
                            $file             = array();
                            $file['name']     = $_FILES['EETPLUS_CERT']['name'];
                            $file['type']     = $_FILES['EETPLUS_CERT']['type'];
                            $file['tmp_name'] = $_FILES['EETPLUS_CERT']['tmp_name'];
                            $file['error']    = $_FILES['EETPLUS_CERT']['error'];
                            $file['size']     = $_FILES['EETPLUS_CERT']['size'];
                            $status           = $this->validateUpload($file);
                            if ($status === false) {
                                $target = _PS_MODULE_DIR_ . $this->instance->name . '/certs/' . $file['name'];
                                Configuration::updateValue('EETPLUS_CERT', $file['name']);
                                if (file_exists($target)) {
                                    unlink($target);
                                }
                                move_uploaded_file($file['tmp_name'], $target);
                            } else {
                                $this->instance->errors[] = $status;
                            }
                        }
                    }
                    Configuration::updateValue('EETPLUS_CERT_PASSWD', Tools::getValue('EETPLUS_CERT_PASSWD'));
                }; break;
                case 2: {
                    $lastVersion = Configuration::get('EETPLUS_VERSION');
                    $currentVersion =$this->instance->version;
                    require_once(_PS_MODULE_DIR_.$this->instance->name.'/classes/EetUpgrade.php');
                    $upgrade = new EetUpgrade();
                    $methods = $upgrade->getAvailableMethods($lastVersion, $currentVersion); 
                    foreach($methods as $method) {
                         $upgrade->$method($this->instance);
                    }
                    Configuration::updateValue('EETPLUS_VERSION', $this->instance->version);
                };
                    break;
            }
        }
        return parent::postProcess();
    }
    
    private function validateUpload($file)
    {
        if (!is_uploaded_file($file['tmp_name']))
            return true;
        if ($file['size'] > Eetplus::CERT_MAXSIZE)
            return true;
        return false;
    }
    
    public function getContent($tabnum)
    {
        $output = '<form action="' . $_SERVER['REQUEST_URI'] . '" method="post" enctype="multipart/form-data">';
        $output .= '<input type="hidden" name="currentTab" value="' . $tabnum . '" />';
        $output .= '<fieldset><legend>' . $this->instance->l('Nastavení', 'EetplusController') . '</legend>';
        $output .= $this->addUpgradeButton();
        $checked = Configuration::get('EETPLUS_SANDBOX') == 1 ? ' checked ="checked"' : '';
        $output .= $this->generateCheckBox('EETPLUS_SANDBOX', $this->instance->l('Testovací prostředí [SANDBOX]', 'EetplusController'), null, $checked);
        $output .= '';
        if (Configuration::get('EETPLUS_SANDBOX') == 1) {
            $output .= '<div style="color:red;text-align:left;font-size:large;clear:both;margin-left:5px;border-bottom:1px solid #d0d0d0;">' . $this->instance->l('V testovacím prostředí nedochází ke skutečné evidenci tržeb') . '</div><br /><br />';
        }
        $output .= $this->generateTextBox('EETPLUS_IDPROVOZ', $this->instance->l('ID provozovny', 'EetplusController'), $this->instance->l('Daňový portál>Služby>EET Evidence tržeb>Provozovny'), 6);
        $output .= $this->generateTextBox('EETPLUS_IDPOKL', $this->instance->l('ID pokladny', 'EetplusController'), $this->instance->l('Zvolte'), 6);
        $output .= $this->generateTextBox('EETPLUS_DICPOPL', $this->instance->l('DIČ poplatníka', 'EetplusController'), $this->instance->l('Daňový portál>Služby>EET Evidence tržeb>Poplatník'), 6);
        $output .= $this->generateTextBox('EETPLUS_IC', $this->instance->l('IČ poplatníka', 'EetplusController'), "", 6);
        $output .= '<div>' . '<a href="https://adisdpr.mfcr.cz/adistc/adis/idpr_pub/eet/eet_sluzby.faces" target="_blank"> ' . $this->instance->l("Vstup do EET") . '</a></div>';
        $output .= '<br />';
        
        $options = array(
        0=>$this->instance->l('Levý sloupec', 'EetplusController'),
        1=>$this->instance->l('Pravý sloupec', 'EetplusController'),
        2=>$this->instance->l('Patička', 'EetplusController'),
        3=>$this->instance->l('Záhlaví', 'EetplusController'),
        4=>$this->instance->l('Ne', 'EetplusController'),
        );
        
        $output.= $this->generateRadio('EETPLUS_TEXT',   $this->instance->l('Umístění povinného textu', 'EetplusController'), null, $options); 
        $output .= $this->generateTextBox('EETPLUS_TEXTCOL', $this->instance->l('Barva textu:', 'EetplusController'), '', 6, false);
   
        $output .= '<br >'.$this->checkReqiurements();
        $output .= $this->generateSubmit('cmd_Settings[0]', $this->instance->l('Uložit', 'EetplusController'));
        $output .= '</fieldset>';
        $output .= '<fieldset><legend>' . $this->instance->l('Správa certifikátů', 'CertsController') . '</legend>';
        if (Configuration::get('EETPLUS_CERT') && file_exists(_PS_MODULE_DIR_ . $this->instance->name . '/certs/' . Configuration::get('EETPLUS_CERT'))) {
            $output .= '<span style="color:green">' . $this->instance->l('Nahraný certifikát', 'EetplusController') . ':   ' . Configuration::get('EETPLUS_CERT') . '</span>';
        } else {
            $output .= '<span style="color:red">' . $this->instance->l('Nemáte nahrán   certifikát pro ostrý provoz', 'EetplusController') . '</span>';
        }
        $output.='<br />';
        if (Configuration::get('EETPLUS_CERT_PASSWD') && strlen(Configuration::get('EETPLUS_CERT_PASSWD') )) {
            $output .= '<span style="color:green">' . $this->instance->l('Heslo je uloženo', 'EetplusController') . ' </span>';
        } else {
            $output .= '<span style="color:red">' . $this->instance->l('Vložte heslo', 'EetplusController') . '</span>';
        }
        $output .= '<div class="form-group"><label class="control-label col-lg-3">' . $this->instance->l('Nahrát certifikát', 'EetplusController') . ' </label>';
        $output .= '<div class="col-lg-9 ">';
        $output .= '<input type="file" name="EETPLUS_CERT" />';
        $output .= '</div> ';
        $output .= '</div>';
        $output .= '<br />' . $this->generatePassword('EETPLUS_CERT_PASSWD', $this->instance->l('Heslo k certifikátu', 'EetplusController'), "", 6);
        $output .= $this->generateSubmit('cmd_Settings[1]', $this->instance->l('Uložit / Nahrát', 'EetplusController'));
        $output .= '</fieldset>';
        $output .= '</form>';
        return $output;
    }
    
    protected function addUpgradeButton() {
      $output = '';
      $lastVersion = Configuration::get('EETPLUS_VERSION');
      $currentVersion =$this->instance->version;
      if($lastVersion == false || ($lastVersion != $currentVersion)) {
          require_once(_PS_MODULE_DIR_.$this->instance->name.'/classes/EetUpgrade.php');
          $upgrade = new EetUpgrade();
          
          $methods = $upgrade->getAvailableMethods($lastVersion, $currentVersion);
              if(count($methods)) {
                  $output.= $this->instance->l('Poslední verze modulu pro kterou byla provedena kontrola instalace: ');
                  if(!$lastVersion ||  empty($lastVersion)) {
                       $output.= $this->instance->l('Neznámá');
                  }
                  else {
                     $output.= $lastVersion;
                  } 
                  $output.='<br />';
                  $output.= $this->instance->l('Aktuální verze modulu: ');
                  $output.= $currentVersion;
                  
                  $output .= $this->generateSubmit('cmd_Settings[2]', $this->instance->l('Zkontrolovat/Opravit instalaci', 'EetplusController'));
                  }
          }
      return $output;
    
      
    }
    
    protected function checkReqiurements()
    {
        $retval = '';
        if (!class_exists('SOAPClient'))
            $retval .= "<h4 class='errormsg'>Na hostingu není dostupná SOAP knihovna !</h4>";
        if (!extension_loaded('curl'))
            $retval .= "<h4 class='errormsg'>Na hostingu není dostupná cUrl knihovna !</h4>";
        if (!extension_loaded('openssl')) {
            $retval .= "<h4 class='errormsg'>Na hostingu není dostupná openssl knihovna !</h4>";  
        }
        else {
                $functions = array('openssl_pkcs12_read', 'openssl_x509_parse', 'openssl_random_pseudo_bytes',
                'openssl_x509_export', 'openssl_get_publickey', 'openssl_get_privatekey', 
                'openssl_public_encrypt', 'openssl_private_encrypt', 'openssl_public_decrypt', 'openssl_private_decrypt', 
                );
                foreach($functions as $function) {

                if(!function_exists($function)) {
                    $retval[] .= "<h4 class='errormsg'> funkce ".$function. " chybí !</h4>"; 
                }
                }
        }
            
            
            
        return $retval;
    }
    
}