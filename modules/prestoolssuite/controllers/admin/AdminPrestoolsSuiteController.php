<?php

/*
 * 2007-2015 PrestaShop
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
 *  @author PrestaShop SA <contact@prestashop.com>
 *  @copyright  2007-2015 PrestaShop SA
 *  @version  Release: $Revision: 13573 $
 *  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *  International Registered Trademark & Property of PrestaShop SA
 */

class AdminPrestoolsSuiteController extends ModuleAdminController
{
	public function __construct()
	{ error_reporting(E_ALL);
	  $this->name = 'prestoolssuite';
	  parent::__construct();
  
	}
	
	private function checkBom($session)
	{ 	if($session)
		{   $file = @fopen("approve.php", "r"); 
			$bom = fread($file, 3); 
			if ($bom == b"\xEF\xBB\xBF") 
			{ echo '<script type="text/javascript">
					alert(\'BOM header found! Use another text editor!\');
				</script>';
			  exit;
			}
		} 
	}
	
	private function get_seed()
	{ $seed = "";
      if(isset($_SERVER['SERVER_SIGNATURE']))   $seed.=$_SERVER['SERVER_SIGNATURE'];
      if(isset($_SERVER['GATEWAY_INTERFACE']))  $seed.=$_SERVER['GATEWAY_INTERFACE'];
      if(isset($_SERVER['SERVER_ADMIN']))       $seed.=$_SERVER['SERVER_ADMIN'];
      return $seed;
	}
	
	public function setMedia($isNewTheme = false)
	{ $musername = Configuration::get('PRESTOOLSSUITE_USERNAME');
	  $mpassword = Configuration::get('PRESTOOLSSUITE_PASSW');
	  $mdirectory = Configuration::get('PRESTOOLSSUITE_DIR');
	  if ((@include $mdirectory."/settings1.php") != TRUE)
	  { // echo "Settings file could not be loaded. Please check the Prestools subdirectory!";
		return parent::setMedia();
	  }
	  // sessions are tricky things that don't work on all servers, so try out first 
	  if(isset($usecookies) && $usecookies)
	  {  $session = false;
	  }
	  else
	  { $session = true;
		$sname = $_SERVER['SERVER_NAME'];
		$sname = str_replace(".","", $sname);
		session_id('t69'.$sname);
		ini_set('date.timezone', @date_default_timezone_get());  //alternative: if(!ini_get('date.timezone')) {date_default_timezone_set('GMT');} 
		if(!@is_writable(session_save_path()))
		  $session = false;
		else
		  session_start();
	  }
	  $validated = false;
	  if($session) 
	  { if (isset($_SESSION['tripleedit']) && $_SESSION['tripleedit'] == 'open')
	      $validated = true;
	  }
      else
	  { $seed = get_seed();
		$encseed = stripslashes(stripslashes(stripslashes(convert_uuencode(base64_encode($seed)))));  // some servers escape cookies
		if(isset($_COOKIE["tripleedit"]) && (stripslashes(stripslashes(stripslashes($_COOKIE["tripleedit"]))) == $encseed))
		   $validated = true;
	  }
	  if(!$validated && (($musername == $username) && (($md5hashed && (md5($mpassword) == $password )) || (!$md5hashed && ($mpassword == $password)))))
	  { if($session)
		{	self::checkBom($session);
			$_SESSION['tripleedit'] = 'open';
			session_write_close();
		}
		else
		{  	$seed = get_seed();
			$encseed = stripslashes(stripslashes(stripslashes(convert_uuencode(base64_encode($seed)))));  // some servers escape cookies
			setcookie("tripleedit", $encseed);
		}
		$validated = true;
	  }
	  if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') 
		  $prefix = "https://";
	  else 
		  $prefix = "http://";
	  $newpath = $prefix.$_SERVER['HTTP_HOST'].dirname($_SERVER["SCRIPT_NAME"])."/".$mdirectory."/product-edit.php";
	  $newpath2 = $mdirectory."/product-edit.php";
//	  if($validated)
	  if(1)
	  { header("Location: ".$newpath);
	    exit;
	  }
	  else
	  {
		  
	  } 
	  return parent::setMedia();
	}
	
	public function initContent()
	{ // $this-> renderView();
	  return parent::initContent();
	}
	
	public function renderView()
	{	
	   if(!($config = $this->loadObject()))
	   { 
            return;
        } 
//		$this->context->smarty->createTemplate('form.tpl', $this->context->smarty);
		$this->base_tpl_view = 'form.tpl';
		        $this->tpl_view_vars = array(
            'username' => "Johny",
            'password' => "geheim",
            'hauteur' => "90283"
            );
//$this->module->display(_PS_MODULE_DIR_.$this->module->name.DIRECTORY_SEPARATOR.$this->module->name.'.php', 'form.tpl'); 
		return parent::renderView();
	}
}

function get_seed()
{ $seed = "";
  if(isset($_SERVER['SERVER_SIGNATURE']))   $seed.=$_SERVER['SERVER_SIGNATURE'];
  if(isset($_SERVER['GATEWAY_INTERFACE']))  $seed.=$_SERVER['GATEWAY_INTERFACE'];
  if(isset($_SERVER['SERVER_ADMIN']))       $seed.=$_SERVER['SERVER_ADMIN'];
  return @date("m Y").$seed."random"._COOKIE_KEY_;
}
