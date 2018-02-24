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

<form id="module_form_1" class="defaultForm form-horizontal" action="index.php?controller=AdminModules&configure=productorderspro&id_supplier={Tools::getValue('id_supplier')|escape:'html':'UTF-8'}&viewsupplier&token={$token_mod|escape:'html':'UTF-8'}" method="post" enctype="multipart/form-data" novalidate="">
<input type="hidden" name="searchProductNka" value="1">				
<div class="panel" id="fieldset_0_1_1">												
<div class="panel-heading">
<i class="icon-search"></i>		
{l s='Search a Product' mod='productorderspro'}
</div>								
<div class="form-wrapper">											
<div class="form-group">													
<label class="control-label col-lg-3">
{l s='Product to search' mod='productorderspro'}
</label>							
<div class="col-lg-9 ">	
<input type="text" name="nka_searched_product" id="nka_searched_product" value="{Tools::getValue('nka_searched_product')|escape:'html':'UTF-8'}" class="fixed-width-xxl">																				
								
<p class="help-block">
{l s='Enter the name of a product you want to search for.' mod='productorderspro'}
</p>																	
</div>							
</div>																
</div><!-- /.form-wrapper -->					
<div class="panel-footer">
<button type="submit" value="1" id="module_form_submit_btn_1" name="searchProductNka" class="btn btn-default pull-right">
<i class="process-icon-refresh"></i>{l s='Search' mod='productorderspro'}
</button>
</div>							
</div>		
</form>

<div class="panel">												
<div class="panel-heading">
<i class="icon-edit"></i>{l s='Search Results' mod='productorderspro'}
<span class="badge">{*$count|escape:'html':'UTF-8'*}</span>
</div>		
<div class="form-wrapper">
</div>
<table class="table product">
<thead>
<tr>
<th><span class="title_box ">{l s='Supplier' mod='productorderspro'}</span></th>
<th><span class="title_box ">{l s='Supplier Reference' mod='productorderspro'}</span></th>

<th><span class="title_box ">{l s='Product' mod='productorderspro'}</span></th>
<th>
<span class="title_box ">{l s='Unit Price' mod='productorderspro'}</span>
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
{foreach from=$searched_products item=product}
<form id="configuration_form" action="index.php?controller=AdminModules&configure=productorderspro&id_supplier={Tools::getValue('id_supplier')|escape:'html':'UTF-8'}&viewsupplier&token={$token_mod|escape:'html':'UTF-8'}"   method="post"    class="defaultForm form-horizontal productorderspro">
<tr class=" odd">
<td class="row-selector text-center">
<input type="hidden" name="id_product" value="{$product.id_product|escape:'html':'UTF-8'}">
{Supplier::getNameById($product.id_supplier|escape:'html':'UTF-8')}
</td>
<td class="">
<p>
{if isset($product.supplier_reference) && $product.supplier_reference!=''} 
{$product.supplier_reference|escape:'html':'UTF-8'}
{else}
<select name="id_product_supplier" >
{foreach from=$suppliers_references item=nka}
{if $nka.id_supplier==$product.id_supplier && $product.id_product==$nka.id_product}
<option value="{$nka.id_product_supplier|escape:'html':'UTF-8'}" selected="selected">{$nka.product_supplier_reference|escape:'html':'UTF-8'}</option>
{/if}
{/foreach}
</select>
{/if}
</p>
</td>	
<td class="">
<a href="index.php?controller=adminproducts&amp;id_product={$product.id_product|escape:'html':'UTF-8'}&amp;updateproduct&amp;token={$token_mod|escape:'html':'UTF-8'}">
<span class="productName">
{$product.name|escape:'html':'UTF-8'}
</span><br>
</a>
</td>
<td class="">
{displayPrice price=$product.price}  
</td>
<td class="">
<input type="text" name="comment" class="edit_product_quantity" value="1">
</td>
<td class="text-right">	
<div class="btn-group-action">				
<div class="btn-group pull-right">
<input type="submit" name="submitAddSearchedProduct" value="{l s='Add Product' mod='productorderspro'}" {literal}onclick="if (confirm('Ajouter ce produit ?')){return true;}else{event.stopPropagation(); event.preventDefault();};"{/literal} title="add product" class="edit btn btn-default">
</div>
</div>
</td>
</tr>
</form>	
{/foreach}
</tbody>
</table>
</div>
<br>