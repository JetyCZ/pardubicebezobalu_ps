<table bgcolor="#ffffff" style="width:auto;border-collapse:collapse">
{if isset($summary.total_shipping_tax_incl)}
 <tr>
  <td style="padding-right:5px;text-align:left;"> 
    {l s='Dopravné' mod='eetplus'}: 
  </td>
  <td>
    {$eetplus->toSmarty($summary.total_shipping_tax_incl)}  {l s='s DPH' mod='eetplus'}
  </td>
</tr>
{/if}
{if isset($summary.total_products_wt)}
  <tr>
  <td style="padding-right:5px;text-align:left;"> 
 {l s='Produkty celkem' mod='eetplus'}: 
  </td>
  <td> 
 {$eetplus->toSmarty($summary.total_products_wt)}  {l s='s DPH' mod='eetplus'}
  </td>
</tr> 
{/if}
{if isset($summary.total_discounts_tax_incl)} 
  <tr>
  <td style="padding-right:5px;text-align:left;"> 
 {l s='Slevy celkem' mod='eetplus'}: 
   </td>
  <td>
 {$eetplus->toSmarty($summary.total_discounts_tax_incl)}  {l s='s DPH' mod='eetplus'}
  </td>
</tr> 
{/if}
{if isset($summary.total_paid_tax_excl)}
  <tr>
  <td style="padding-right:5px;text-align:left;"> 
 {l s='Cena celkem' mod='eetplus'}: 
  </td>
  <td> 
 {$eetplus->toSmarty($summary.total_paid_tax_excl)}  {l s='bez DPH' mod='eetplus'}
  </td>
</tr> 
{/if}
</table>
<br />        