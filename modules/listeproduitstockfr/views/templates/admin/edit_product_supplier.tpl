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
<form id="configuration_form" class="defaultForm form-horizontal productorderspro">
<h2>{l s='Product Suppliers' mod='productorderspro'}</h2>
<div class="panel">
<div class="panel-heading">
<i class="icon-edit"></i>{l s='Order Associated  Products & Suppliers' mod='productorderspro'}
<span class="badge">{*$count|escape:'html':'UTF-8'*}</span>
</div>	
<div class="table-responsive">
<table class="table" id="orderProducts">
<thead>
<tr>
<th><span class="title_box ">{l s='Supplier' mod='productorderspro'}</span></th>
<th><span class="title_box ">{l s='Supplier Reference' mod='productorderspro'}</span></th>

<th><span class="title_box ">{l s='Product' mod='productorderspro'}</span></th>
<th>
<span class="title_box ">{l s='Unit Price' mod='productorderspro'}</span>
<small class="text-muted">
All taxes included.
</small>
</th>
<th class="text-center">
<span class="title_box ">Quantity
</span>
</th>
<th class="text-center"><span class="title_box ">Available Quantity</span></th>	
<th>
<span class="title_box ">Total</span>
<small class="text-muted">
All taxes included.
</small>
</th>
<th style="display: none;" class="add_product_fields"></th>
<th style="display: none;" class="edit_product_fields" colspan="2"></th>
<th style="display: none;" class="standard_refund_fields">
<i class="icon-minus-sign"></i>
Cancel
</th>
<th style="display:none" class="partial_refund_fields">
<span class="title_box ">Refund partially</span>
</th>
<th></th>
</tr>
</thead>
<tbody>	
{foreach from=$products item=product}
<tr class="product-line-row">
<td>
{*$product.id_supplier|escape:'html':'UTF-8'*}
<input type="hidden" name="id_supplier_default" value="{$product.id_supplier|escape:'html':'UTF-8'}">
{Supplier::getNameById($product.id_supplier|escape:'html':'UTF-8')}
{if $suppliers}			
<p>
<select name="id_supplier" >
<option value="0">{l s='All suppliers' mod='productorderspro'}</option>
{foreach from=$suppliers item=supplier}
<option value="{$supplier.id_supplier|escape:'html'}">{$supplier.name|escape:'html':'UTF-8'}</option>
{/foreach}
</select>
</p>
{/if}
</td>
<td>
{$product.product_supplier_reference|escape:'html':'UTF-8'}
</td>
<td>
<a href="index.php?controller=adminproducts&amp;id_product={$product.id_product|escape:'html':'UTF-8'}&amp;updateproduct&amp;token={$prod_token|escape:'html':'UTF-8'}">
<span class="productName">{$product.product_name|escape:'html':'UTF-8'} </span><br>
</a>
<input type="hidden" name="id_product" value="{$product.id_product|escape:'html':'UTF-8'}">
<input type="hidden" name="id_supplier" value="{$product.id_supplier|escape:'html':'UTF-8'}">
<input type="hidden" name="id_order" value="{$product.id_order|escape:'html':'UTF-8'}">
</td>
<td>
<span class="product_price_show" style="display: inline;">{displayPrice price=$product.product_price|escape:'html':'UTF-8'} </span>
<div class="product_price_edit" style="display: none;">
<input type="hidden" name="product_id_order_detail" class="edit_product_id_order_detail" value="{$product.id_order_detail|escape:'html':'UTF-8'}">
<div class="form-group">
<div class="fixed-width-xl">
<div class="input-group">
<input type="text" name="product_price_tax_excl" class="edit_product_price_tax_excl edit_product_price" value="{$product.product_price|escape:'html':'UTF-8'}">
</div>
</div>
<br>
<div class="fixed-width-xl">
<div class="input-group">
<input type="text" name="product_price_tax_incl" class="edit_product_price_tax_incl edit_product_price" value="{$product.product_price|escape:'html':'UTF-8'}">
</div>
</div>
</div>
</div>
</td>
<td class="productQuantity text-center">
<span class="product_quantity_show" style="display: inline;">{$product.product_quantity|escape:'html':'UTF-8'}</span>
<span class="product_quantity_edit" style="display: none;">
<input type="text" name="product_quantity" class="edit_product_quantity" value="{$product.product_quantity|escape:'html':'UTF-8'}">
</span>
</td>
<td class="productQuantity product_stock text-center">{$product.current_stock|escape:'html':'UTF-8'}</td>
<td class="total_product">
		{displayPrice price=$product.total_price}
</td>
<td class="product_action text-right" colspan="1">		
<div class="btn-group" style="display: inline-block;">
	<button type="button" class="btn btn-default edit_product_change_link">
	<i class="icon-pencil"></i>
				{l s='Add Product' mod='productorderspro'}
	</button>
	<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
	<span class="caret"></span>
	</button>
	<ul class="dropdown-menu" role="menu">
		<li>
		<a href="#" class="delete_product_line">
		<i class="icon-trash"></i>
		{l s='Delete' mod='productorderspro'}
		</a>
		</li>
	</ul>
</div>		
    
	<button type="button" class="btn btn-default submitProductChange" style="display: none;">
			<i class="icon-ok"></i>
			{l s='Add Product' mod='productorderspro'}
	</button>
	
	<button type="button" class="btn btn-default cancel_product_change_link" style="display: none;">
			<i class="icon-remove"></i>
			{l s='Cancel' mod='productorderspro'}
	</button>
</td>
	</tr>
{/foreach}		
</tbody>
</table>
</div>
<div style="display: none;" class="standard_refund_fields form-horizontal panel">
<div class="form-group">
</div>
<div class="row">
<input type="submit" name="cancelProduct" value="Annuler les produits" class="btn btn-default">
</div>
</div>
<div class="panel-footer">
<a href="{$url_back_order|escape:'html':'UTF-8'}" class="btn btn-default">
<i class="process-icon-back"></i>{l s='Back to list' mod='productorderspro'}</a>
</div>
</div>
</form>	

{include file="./invoice_supplier.tpl"}	