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
 {l s='Supplier Order List' mod='productorderspro'}</div>
<div class="table-responsive-row clearfix">
<table class="table supplier">
<thead>
<tr class="nodrag nodrop">
<th class="center fixed-width-xs"></th>
<th class="">
<span class="title_box">
{l s='ID Supplier' mod='productorderspro'}
</span>
</th>
<th class="">
<span class="title_box">
{l s='Supplier Title' mod='productorderspro'}
</span>
</th>
<th class="">
<span class="title_box">
{l s='Supplier Email' mod='productorderspro'}
</span>
</th>
<th class="">
<span class="title_box">
{l s='Order Minimum Allowed' mod='productorderspro' }
</span>
</th>
<th></th>
</tr>
</thead>
<tbody>
{foreach from=$suppliers item=supplier}
{foreach from=$email_list item=list}
{if ($list.id_supplier==$supplier.id_supplier) && $list.email==''}
<h6 style="color:red;">{l s='Please add email for the following suppliers:' mod='productorderspro'}</h6>
{$supplier.name|escape:'html':'UTF-8'}
{/if}
{/foreach}

<tr  style="odd" >
<td class="row-selector text-center">
<input type="checkbox" name="supplierBox[]" value="1" class="noborder">
</td>							
<td class="">
{$supplier.id_supplier|escape:'html':'UTF-8'}
</td>				
<td class="">	
{$supplier.name|escape:'html':'UTF-8'}	
</td>
<td class="">
{ProductOrdersPro::getEmailSupplier($supplier.id_supplier|escape:'html':'UTF-8')}
</td>
<td class="">
{displayPrice price=ProductOrdersPro::getMiniOrderAmount($supplier.id_supplier|escape:'html':'UTF-8')}
</td>
<td class="btn-group pull-right">
<a href="index.php?controller=AdminModules&amp;configure=productorderspro&amp;id_supplier={$supplier.id_supplier|escape:'html':'UTF-8'}&amp;viewsupplier&amp;token={$semail_token|escape:'html':'UTF-8'}" title="Modifier" class="edit btn btn-default">
<i class="icon-pencil"></i>{l s='Edit' mod='productorderspro'}
</a>
<button class="btn btn-default dropdown-toggle" data-toggle="dropdown">
<i class="icon-caret-down"></i>&nbsp;
</button>
<ul class="dropdown-menu">
<li>
<a href="index.php?controller=AdminModules&amp;configure=productorderspro&amp;id_supplier={$supplier.id_supplier|escape:'html':'UTF-8'}&amp;addsupplieremail&amp;token={$semail_token|escape:'html':'UTF-8'}" class="btn btn-default" title="Afficher">
<i class="icon-edit"></i> {l s='Add Email' mod='productorderspro'}
</a>
</li>
<li class="divider">
</li>
<li>
<a href="index.php?controller=AdminModules&amp;configure=productorderspro&amp;id_supplier={$supplier.id_supplier|escape:'html':'UTF-8'}&amp;viewsupplier&amp;token={$semail_token|escape:'html':'UTF-8'}" class="btn btn-default" title="Afficher">
	<i class="icon-search-plus"></i>{l s='View order' mod='productorderspro'}
</a>
</li>
</ul>
</td>
</tr>
{/foreach}
</tbody>
</table>
</div>
<input type="hidden" name="token" value="{$semail_token|escape:'html':'UTF-8'}">
</div>


