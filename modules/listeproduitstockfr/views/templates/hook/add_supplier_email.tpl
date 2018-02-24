{*
* 2007-2016 PrestaShop
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
*  @copyright  2007-2016 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

<div class="col-lg-12">
<form id="customer_form" class="defaultForm form-horizontal AdminCustomers" action="index.php?controller=AdminModules&configure=productorderspro&token={$save_token|escape:'html':'UTF-8'}&id_supplier={Tools::getValue('id_supplier')|escape:'html':'UTF-8'}" method="post" enctype="multipart/form-data" novalidate="">
<input type="hidden" name="id_supplier" id="id_supplier" value="{Tools::getValue('id_supplier')|escape:'html':'UTF-8'}">			
<div class="panel" id="fieldset_0">												
<div class="panel-heading">
<i class="icon-user"></i>
{l s='Add Supplier Email' mod='productorderspro'}
</div>								
<div class="form-wrapper">											
<div class="form-group">													
<label class="control-label col-lg-3 required">
{l s=' Address e-mail' mod='productorderspro'}
</label>					
<div class="col-lg-4 ">
<div class="input-group">
<span class="input-group-addon">
<i class="icon-envelope-o"></i>
</span>
<input type="text" name="email" id="email" value="{if isset($email) && $email}{$email|escape:'html':'UTF-8'}{/if}" class="" autocomplete="off" required="required">									
</div>																
</div>							
</div>																	
</div><!-- /.form-wrapper -->											
<div class="panel-footer">
<button type="submit"  id="customer_form_submit_btn" name="AddSupplierEmail" class="btn btn-default pull-right">
<i class="process-icon-save"></i> {l s='Save' mod='productorderspro'}
</button>
<a href="index.php?controller=AdminModules&configure=productorderspro&amp;token={$save_token|escape:'html':'UTF-8'}" class="btn btn-default" onclick="window.history.back();">
<i class="process-icon-cancel"></i> {l s='Cancel' mod='productorderspro'}
</a>
</div>							
</div>		
</form>
</div>