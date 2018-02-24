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
<div class="panel-heading">
<i class="icon-edit"></i>{l s='Add New Product' mod='productorderspro'}
<span class="badge">{*$count|escape:'html':'UTF-8'*}</span>
</div>		
<div class="form-wrapper">
</div>
<table class="table product">
<thead>
<tr>
<th><span class="title_box ">{l s='Supplier' mod='productorderspro'}</span></th>
<th><span class="title_box ">{l s='Supplier Reference' mod='productorderspro'}</span></th>
<th><span class="title_box ">{l s='Product Reference' mod='productorderspro'}</span></th>

<th><span class="title_box ">{l s='Product Name' mod='productorderspro'}</span></th>
<th>
<span class="title_box ">{l s='Price' mod='productorderspro'}</span>
<small class="text-muted">
{l s='All taxes included.' mod='productorderspro'}
</small>
</th>
<th class="text-center">
<span class="title_box ">{l s='Quantity' mod='productorderspro'}
</span>
</th>
<th style="display: none;" class="add_product_fields"></th>
<th style="display: none;" class="edit_product_fields" colspan="2"></th>
<th style="display: none;" class="standard_refund_fields">
<i class="icon-minus-sign"></i>
{l s='Cancel' mod='productorderspro'}
</th>
<th style="display:none" class="partial_refund_fields">
<span class="title_box ">...</span>
</th>
<th></th>
</tr>
</thead>
<tbody>
<form id="configuration_form" action="index.php?controller=AdminModules&configure=productorderspro&id_supplier={Tools::getValue('id_supplier')|escape:'html':'UTF-8'}&viewsupplier&token={$token_mod|escape:'html':'UTF-8'}"   method="post"    class="defaultForm form-horizontal productorderspro">
<tr class=" odd">
<td class="row-selector text-center">
{Supplier::getNameById(Tools::getValue('id_supplier'))|escape:'html':'UTF-8'}
{if $suppliers}			
<p>
<select name="id_supplier" >
<option value="{Tools::getValue('id_supplier')|escape:'html':'UTF-8'}">{Supplier::getNameById(Tools::getValue('id_supplier'))|escape:'html':'UTF-8'}</option>
{foreach from=$suppliers item=supplier}
<option value="{$supplier.id_supplier|escape:'html'}">{$supplier.name|escape:'html':'UTF-8'}</option>
{/foreach}
</select>
</p>
{/if}
</td>
<input  type="hidden" name="description_short" value="product of {Supplier::getNameById(Tools::getValue('id_supplier'))|escape:'html':'UTF-8'}">
<input  type="hidden" name="description" value="product of {Supplier::getNameById(Tools::getValue('id_supplier'))|escape:'html':'UTF-8'}">
<input  type="hidden" name="link_rewrite" value="{Tools::getValue('product_name')|escape:'html':'UTF-8'}">
<input  type="hidden" name="supplier_name" value="{Supplier::getNameById(Tools::getValue('id_supplier'))|escape:'html':'UTF-8'}">
<td class="">
<input  type="text" name="product_supplier_reference">
</td>
<td class="">
<input  type="text" name="product_reference">
</td>		
<td class="">
<span class="productName">
<input  type="text" name="product_name">
</span>
</td>
<td class="">  
<input  type="text" name="product_price">
</td>
<td class="">
<input type="text" name="comment" class="edit_product_quantity" value="1">
</td>
<td class="text-right">	
<div class="btn-group-action">				
<div class="btn-group pull-right">
<input type="submit" name="submitAddNewProduct" value="{l s='Add New Product' mod='productorderspro'}" {literal}onclick="if (confirm('Ajouter ce produit ?')){return true;}else{event.stopPropagation(); event.preventDefault();};"{/literal} title="add product" class="edit btn btn-default">
</div>
</div>
</td>
</tr>
</form>	
</tbody>
</table>
</div>
<br>