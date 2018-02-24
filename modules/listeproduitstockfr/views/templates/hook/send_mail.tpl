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

<div class="panel">
<form id="customer_form" class="defaultForm form-horizontal productorderspro" action="index.php?controller=AdminModules&configure=productorderspro&token={$token_mod|escape:'html':'UTF-8'}&viewsupplier" method="post" enctype="multipart/form-data" novalidate="">
<div class="panel" id="fieldset_0">												
<div class="panel-heading">
<i class="icon-envelope-o"></i>
{l s='Send mail to Supplier' mod='productorderspro' }
</div>								
<div class="form-wrapper">											
<div class="form-group">													
<label class="control-label col-lg-3 required">
{l s='Enter your Supplier\'s Email' mod='productorderspro'}
</label>					
<div class="col-lg-4 ">
<div class="input-group">
<span class="input-group-addon">
<i class="icon-envelope-o"></i>
</span>
<input type="text" name="email_supplier" value="{$email|escape:'html':'UTF-8'}">
<input type="hidden" name="id_supplier" value="{Tools::getValue('id_supplier')|escape:'html':'UTF-8'}">
<input type="hidden" name="id_product" value="{Tools::getValue('id_product')|escape:'html':'UTF-8'}">							
</div>																
</div>							
</div>	<!--Title--><div class="form-group">													
<label class="control-label col-lg-3 required"><span class="label-tooltip" data-toggle="tooltip" data-html="true" title="" data-original-title="">{l s='Subject' mod='productorderspro'}</span></label>				
<div class="col-lg-4 ">	<input type="text" name="message_title" id="message_title" value="{if isset($id_country) && $id_country==8}Nouvelle commande{else}New order{/if}" class="" required="required">											
	</div></div><!--end title--><!--Subject-->
<!--<div class="form-group">												
	<label class="control-label col-lg-3 "><span class="label-tooltip" data-toggle="tooltip" data-html="true" title="" data-original-title="">Message	</span></label>		
	<div class="col-lg-4 ">	<textarea rows="10" cols="10" name="message_subject" id="message_subject"  class="" >{$subject|escape:'html':'UTF-8'}</textarea>												
	</div></div>--><!--end Subject-->																
</div><!-- /.form-wrapper -->											
<div class="panel-footer">
<input type="submit" name="submit_Envoyer"  value="{l s='Send Email to ' mod='productorderspro'}{$supplier|escape:'html':'UTF-8'}"  class="btn btn-default pull-right">
</div>							
</div>		
</form>
</div>