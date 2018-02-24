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
<h2>{l s='Product Suppliers' mod='productorderspro'}</h2>
<div class="panel">												
<div class="panel-heading">
<i class="icon-edit"></i>{l s='Order Associated  Products & Suppliers' mod='productorderspro'}
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

<th><span class="title_box ">{l s='Product' mod='productorderspro'}</span></th>
<!--<th>
<span class="title_box ">{l s='Unit Price' mod='productorderspro'}</span>
<small class="text-muted">
All taxes included.
</small>
</th>-->
<th class="text-center">
<span class="title_box ">{l s='Quantity' mod='productorderspro'}
</span>
</th>
<th class="text-center"><span class="title_box ">{l s='Available Quantity' mod='productorderspro'}</span></th>	
<!--<th>
<span class="title_box ">Total</span>
<small class="text-muted">
All taxes included.
</small>
</th>-->
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
{foreach from=$products item=product}
<form id="configuration_form" action="index.php?controller=AdminOrders&id_order={$id_order|intval}&id_product={$product.id_product|escape:'html':'UTF-8'}&id_supplier={$product.id_supplier|escape:'html':'UTF-8'}&vieworder&OrderInnovativeSupplier&token={$token|escape:'html':'UTF-8'}"   method="post"    class="defaultForm form-horizontal productorderspro">
<tr class=" odd">
<td class="row-selector text-center">
{*$product.id_supplier|escape:'html':'UTF-8'*}
<input type="hidden" name="id_supplier_default" value="{$product.id_supplier|escape:'html':'UTF-8'}">
{Supplier::getNameById($product.id_supplier|escape:'html':'UTF-8')}
{if $suppliers}			
<p>
<select name="id_supplier" >
<option value="{$product.id_supplier|escape:'html':'UTF-8'}">{Supplier::getNameById($product.id_supplier|escape:'html':'UTF-8')}</option>
{foreach from=$suppliers item=supplier}
<option value="{$supplier.id_supplier|escape:'html'}">{$supplier.name|escape:'html':'UTF-8'}</option>
{/foreach}
</select>
</p>
{/if}
</td>
<td class="">
<p>
{if isset($product.supplier_reference) && $product.supplier_reference!=''} 
{$product.supplier_reference|escape:'html':'UTF-8'}
{else}
<select name="id_product_supplier" >
{foreach from=$suppliers_references item=nka}
{if $nka.id_supplier==$product.id_supplier && $product.id_product==$nka.id_product}
<option value="{$nka.id_product_supplier|escape:'html':'UTF-8'}" selected="selected">{$nka.product_supplier_reference|escape:'html':'UTF-8'}
:Price: {displayPrice price=ProductOrderPro::getSupplierPrice({$nka.id_product_supplier|escape:'html':'UTF-8'})}</option>
{/if}
{/foreach}
</select>
{/if}
</p>
</td>
<td class="">
{$product.reference|escape:'html':'UTF-8'} 	
</td>		
<td class="">
<a href="index.php?controller=adminproducts&amp;id_product={$product.id_product|escape:'html':'UTF-8'}&amp;updateproduct&amp;token={$prod_token|escape:'html':'UTF-8'}">
<span class="productName">{$product.product_name|escape:'html':'UTF-8'} </span><br>
</a>
</td>
<!--<td class="">
{*displayPrice price=$product.product_price*}  
</td>-->
<td class="">
<input type="text" name="product_quantity" class="edit_product_quantity" value="{$product.product_quantity|escape:'html':'UTF-8'}">
</td>
<td class="">
{$product.current_stock|escape:'html':'UTF-8'}
</td>
<!--<td class="total_product">
{*displayPrice price=$product.total_price*}
</td>-->
<td class="text-right">	
<div class="btn-group-action">				
<div class="btn-group pull-right">
<input type="submit" name="OrderInnovativeSupplier" value="{l s='Add Product' mod='productorderspro'}" {literal}onclick="if (confirm('Ajouter ce produit ?')){return true;}else{event.stopPropagation(); event.preventDefault();};"{/literal} title="add product" class="edit btn btn-default">

</div>
</div>
</td>
</tr>
</form>	
{/foreach}	
</tbody>
</table>
<div class="panel-footer">
<a href="{$url_back_order|escape:'html':'UTF-8'}" class="btn btn-default">
<i class="process-icon-back"></i>{l s='Back to list' mod='productorderspro'}</a>
</div>
</div>

<br>	


