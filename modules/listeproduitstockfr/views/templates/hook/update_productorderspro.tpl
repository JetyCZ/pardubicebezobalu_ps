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
<div class="panel">												
<div class="panel-heading">
<i class="icon-edit"></i>{$supplier|escape:'html':'UTF-8'}{l s=' Ordered  Products' mod='productorderspro'}
<span class="badge">{$count|escape:'html':'UTF-8'}</span>
</div>	
<h3 style="color:red">{l s='Order Minimum allowed:' mod='productorderspro'}{displayPrice price=$sp_order_minimum_nka}</h3>
<p style="color:green">{l s='Supplier:' mod='productorderspro'}  {$supplier|escape:'html':'UTF-8'}</p><h2 style="color:green">{l s='Order Total:' mod='productorderspro'}  {displayPrice price=$total_reappro_summ|escape:'html':'UTF-8'}</h2>

<div class="form-wrapper">{if isset($id_country) && $id_country==8}
{l s='Country:' mod='productorderspro' }{$pays|escape:'html':'UTF-8'}{if isset($sp_minimum_allowed) && $sp_minimum_allowed==1 && $total_reappro_summ>=$sp_order_minimum_nka}
<button style="background:green;color:white;" >{l s='Order Allowed' mod='productorderspro'}</button>
{else}<button style="background:red;color:white;" >{l s='Order Not Allowed' mod='productorderspro'}</button><p style="color:red">{l s='Your order is lower than the minimum allowed:' mod='productorderspro'}</p><p>{l s='Minimum allowed:' mod='productorderspro' } {displayPrice price=$sp_order_minimum_nka|escape:'html':'UTF-8'}</p><p style="color:red">{l s='Your Order Total:' mod='productorderspro' } {displayPrice price=$total_reappro_summ|escape:'html':'UTF-8'}</p>{/if}{/if}</div>
<table class="table product">
<thead>
<tr class="nodrag nodrop">
<th class="center fixed-width-xs"></th>
<th class="">
<span class="title_box">
{l s='ID' mod='productorderspro'}
</span>
</th>
<th class="">
<span class="title_box">
{l s='Product Reference' mod='productorderspro'}
</span>
</th>
<th class="">
<span class="title_box">
{l s='Supplier Reference' mod='productorderspro'}
</span>
</th>
<th class="">
<span class="title_box">
{l s='Product Name' mod='productorderspro'}
</span>
</th>
<th class="">
<span class="title_box">
{l s='Quantity' mod='productorderspro'}
</span>
</th>
<th class="">
<span class="title_box">
{l s=' Unit  Price ' mod='productorderspro'}
</span>
</th>
<th class="">
<span class="title_box">
{l s='Total Price' mod='productorderspro'}
</span>
</th>
<th></th>
</tr>
</thead>
<tbody>
{foreach from=$product_suppliers item=product }  
<tr class=" odd">
<td class="row-selector text-center">
<input type="checkbox" name="productBox[]" value="" class="noborder">
</td>
<td class="">{$product.id_product|escape:'html':'UTF-8'}</td>	
<td class="">{$product.reference|escape:'html':'UTF-8'}</td>	
<td class="">
<p>
{if isset($product.supplier_reference) && $product.supplier_reference!=''} 
{$product.supplier_reference|escape:'html':'UTF-8'}
{else}
{ProductOrderPro::getSupplierReference({$product.id_supplier_reference|escape:'html':'UTF-8'})|escape:'html':'UTF-8'}
{/if}
</p>
</td>			
<td class="">
<a href="index.php?tab=AdminProducts&id_product={$product.id_product|escape:'html':'UTF-8'}&updateproduct&token={$token_prod|escape:'html':'UTF-8'}">{$product.name|escape:'html':'UTF-8'}</a>    
</td>
<td class="">
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{$product.comment|escape:'html':'UTF-8'} 
</td>
<td class="">
{if ProductOrderPro::getSupplierPrice({$product.id_supplier_reference})!=0}
{displayPrice price=ProductOrderPro::getSupplierPrice({$product.id_supplier_reference|escape:'html':'UTF-8'})}
{else}
{displayPrice price=$product.wholesale_price} 
{/if}
</td>
<td class="">
{if ProductOrderPro::getSupplierPrice({$product.id_supplier_reference})!=0}
{displayPrice price=ProductOrderPro::getSupplierPrice({$product.id_supplier_reference|escape:'html':'UTF-8'})*$product.comment} 
{else}
{displayPrice price= $product.wholesale_price*$product.comment} 
{/if}
</td>
<!-- nka -->
<td class="text-right">				
<div class="btn-group-action">				
<div class="btn-group pull-right">
<a href="index.php?controller=AdminSuppliers&id_supplier={Tools::getValue('id_supplier')|escape:'html':'UTF-8'}&viewsupplier&token={$token|escape:'html':'UTF-8'}" title="Modifier" class="edit btn btn-default">
	<i class="icon-pencil"></i> {l s='View' mod='productorderspro'}
</a>
<button class="btn btn-default dropdown-toggle" data-toggle="dropdown">
<i class="icon-caret-down"></i>&nbsp;
</button>
<ul class="dropdown-menu">
<li>
<a href="index.php?controller=AdminModules&configure=productorderspro&id_supplier={Tools::getValue('id_supplier')|escape:'html':'UTF-8'}&viewsupplier&id_innovatives_supplier={$product.id_innovatives_supplier|escape:'html':'UTF-8'}&deletenka_productorderspro&token={$token_mod|escape:'html':'UTF-8'}" {literal}onclick="if (confirm('Supprimer cet élément ?')){return true;}else{event.stopPropagation(); event.preventDefault();};"{/literal} title="Supprimer" class="delete">
<i class="icon-trash"></i>{l s='Delete' mod='productorderspro'}
</a>
</li>
</ul>
</div>
</div>
</td>
</tr>
{/foreach}
</tbody>
</table>
<div class="panel-footer">
<a href="{$url_back|escape:'html':'UTF-8'}" class="btn btn-default">
<i class="process-icon-back"></i>{l s='Back to list' mod='productorderspro'}</a>



<a href="index.php?controller=AdminModules&configure=productorderspro&id_supplier={Tools::getValue('id_supplier')|escape:'html':'UTF-8'}&viewsupplier&submitDeleteReappro&token={$token_mod|escape:'html':'UTF-8'}" {literal}onclick="if (confirm('Supprimer cet élément ?')){return true;}else{event.stopPropagation(); event.preventDefault();};"{/literal} title="Supprimer"     class="btn btn-default" style="float:right;">
<i class="icon-trash"></i>{l s='Delete Order' mod='productorderspro'}
</a>

</div>
</div>
</form>	
<br/>

{include file="./add_custom_product.tpl"}
<br/>
{include file="./search_product.tpl"}
<br/>
<div class="panel-footer">
<p style="color:green">{l s='Supplier:' mod='productorderspro' } {$supplier|escape:'html':'UTF-8'}</p><h2 style="color:green">{l s='Order Total:' mod='productorderspro' } {displayPrice price=$total_reappro_summ}</h2>

{include file="./send_mail.tpl"}</div>

