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


  {if isset($data.dph)}
	<div class="panel-heading">
   <i class="icon-money"></i>
   {l s='Rozpis DPH' mod='eetplus'}
  </div>   
	<table id="tax-tab" width="auto">
		<thead>
			<tr>
				<th style="border:1px solid #D6D4D4;padding:5px 20px;text-align:left;background-color:#f8f8f8;font-family: Arial;font-size: 13px;">{l s='Sazba' mod='eetplus'}</th>
				<th style="border:1px solid #D6D4D4;padding:5px 20px;text-align:left;background-color:#f8f8f8;font-family: Arial;font-size: 13px;">{l s='Základ daně' mod='eetplus'}</th>
				<th style="border:1px solid #D6D4D4;padding:5px 20px;text-align:left;background-color:#f8f8f8;font-family: Arial;font-size: 13px;">{l s='Daň' mod='eetplus'}</th>
			</tr>
		</thead>
		<tbody>
		{foreach from=$data.dph item=sazba} 
        {if isset($sazba.zaklad)}
        <tr>
          <td style="border:1px solid #D6D4D4;padding:5px 20px;">{$sazba.rate} %</td>
          <td style="border:1px solid #D6D4D4;padding:5px 20px;text-align:right;">{$eetplus->toSmarty($sazba.zaklad)}</td>
          <td style="border:1px solid #D6D4D4;padding:5px 20px;text-align:right;">{$eetplus->toSmarty($sazba.dan)}</td>
        </tr>
        {/if}
        {/foreach}
		</tbody>
	</table>
 {/if}
 
 {if isset($data.pouzit_zboz)}  
 <br />
     {foreach from=$data.pouzit_zboz item=pouzite} 
         {l s='Použité zboží sazba' mod='eetplus'}   {$pouzite.rate} % cena celkem: {$eetplus->toSmarty($pouzite.celkem)}<br />
     {/foreach}
 {/if}   



