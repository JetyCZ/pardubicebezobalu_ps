<table class="table table-recap" bgcolor="#ffffff" style="width:100%;border-collapse:collapse"><!-- Title -->
<tr>
<th bgcolor="#f8f8f8" style="border:1px solid #D6D4D4;background-color: #fbfbfb;color: #333;font-family: Arial;font-size: 13px;padding: 10px;text-align:left;">{l s='Produkt' mod='eetplus'}</th>
<th bgcolor="#f8f8f8" style="border:1px solid #D6D4D4;background-color: #fbfbfb;color: #333;font-family: Arial;font-size: 13px;padding: 10px;">{l s=' Jedn. cena' mod='eetplus'}</th>
<th bgcolor="#f8f8f8" style="border:1px solid #D6D4D4;background-color: #fbfbfb;color: #333;font-family: Arial;font-size: 13px;padding: 10px;" width="17%">{l s='Počet' mod='eetplus'}</th>
<th bgcolor="#f8f8f8" style="border:1px solid #D6D4D4;background-color: #fbfbfb;color: #333;font-family: Arial;font-size: 13px;padding: 10px;">{l s='Celková cena' mod='eetplus'} </th>
</tr>            
   
{foreach from=$products item=product}
 <tr>
  <td style="border:1px solid #D6D4D4;padding:5px;text-align:left;">{if isset($product.product_name)} {$product.product_name} {/if}</td>
  <td style="border:1px solid #D6D4D4;padding:5px;text-align:center;">{if isset($product.unit_price_tax_incl)}  {$eetplus->toSmarty($product.unit_price_tax_incl)}   {/if}</td> 
  <td style="border:1px solid #D6D4D4;padding:5px;text-align:center;">{if isset($product.product_quantity)}     {$product.product_quantity} {/if}</td> 
  <td style="border:1px solid #D6D4D4;padding:5px;text-align:center;">{if isset($product.total_price_tax_incl)} {$eetplus->toSmarty($product.total_price_tax_incl)}  {/if}</td>
</tr>
{/foreach}
</table>
<br />