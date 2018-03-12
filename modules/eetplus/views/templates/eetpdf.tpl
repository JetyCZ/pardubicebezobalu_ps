 
 {l s='ELEKTRONICKÁ ÚČTENKA' pdf='true'} č. {$data.poradove_cislo}<br /> 
         
 <b>{$shop_name}</b><br /> 
 {if isset($data.adresa)}
 {if isset($data.adresa.adresa)}<span style="font-size: 10pt;">{$data.adresa.adresa}</span><br />{/if}
 {if isset($data.adresa.zip)}<span style="font-size: 10pt;">{$data.adresa.zip}</span> {/if}
 {if isset($data.adresa.city)}<span style="font-size: 10pt;">{$data.adresa.city}</span><br />{/if}
 {/if}
 
<span style="font-size: 10pt;">DIČ: {$data.dic_popl}</span><br />
<span style="font-size: 10pt;">IČ: {$data.ic_popl}</span><br />
                    
 <span style="font-size: 10pt;">{l s='Provozovna' pdf='true'}: {$data.id_provoz}</span> <br /> 
 <span style="font-size: 10pt;">{l s='Pokladna' pdf='true'}: {$data.id_pokl}</span> <br /> 
 <span style="font-size: 10pt;">{l s='Datum a čas' pdf='true'}: {$data.datum}</span><br /> <br /> 
            
{if isset($data.produkty)}  
 <table cellpadding="5" cellspacing="0"> 
<tr>
<th style="font-size: 10pt;background-color:#e1e1e1;border:1px solid #a0a0a0;width:55%;">{l s='Produkt' pdf='true'}</th>
<th style="font-size: 10pt;background-color:#e1e1e1;border:1px solid #a0a0a0;text-align:right;width:15%">{l s=' Jedn. cena' pdf='true'}</th>
<th style="font-size: 10pt;background-color:#e1e1e1;border:1px solid #a0a0a0;text-align:center;width:15%">{l s='Počet' pdf='true'}</th>
<th style="font-size: 10pt;background-color:#e1e1e1;border:1px solid #a0a0a0;text-align:right;width:15%">{l s='Celková cena' pdf='true'}</th>
</tr>            
   
{foreach from=$data.produkty item=product}
 <tr>
  <td style="font-size: 10pt;border:1px solid #a0a0a0;text-align:left;">{if isset($product.product_name)}{$product.product_name} {/if}</td>
  <td style="font-size: 10pt;border:1px solid #a0a0a0;text-align:right">{if isset($product.unit_price_tax_incl)}{$eetplus->toSmarty($product.unit_price_tax_incl)}{/if}</td> 
  <td style="font-size: 10pt;border:1px solid #a0a0a0;text-align:center;">{if isset($product.product_quantity)}{$product.product_quantity}{/if}</td> 
  <td style="font-size: 10pt;border:1px solid #a0a0a0;text-align:right;">{if isset($product.total_price_tax_incl)}{$eetplus->toSmarty($product.total_price_tax_incl)}{/if}</td>
</tr>
{/foreach}
</table>
<br /> 

{/if}
  
  
  
  
{if isset($data.total_discounts_tax_incl)}                                          
<span style="font-size: 10pt;">{l s='Celkem slevy' pdf='true'}:   {$eetplus->toSmarty($data.total_discounts_tax_incl)} {l s='s DPH' pdf='true'}</span>  <br />
{/if}
{if isset($data.total_shipping_tax_incl)}   
<span style="font-size: 10pt;">{l s='Dopravné' pdf='true'}:   {$eetplus->toSmarty($data.total_shipping_tax_incl)}   {l s='s DPH' pdf='true'}</span>   <br />
{/if}
{if isset($data.total_products_wt)}   
<span style="font-size: 10pt;">{l s='Produkty' pdf='true'}:   {$eetplus->toSmarty($data.total_products_wt)}   {l s='s DPH' pdf='true'}</span>   <br />
{/if}
{if isset($data.total_paid_tax_incl)}   
<span style="font-size: 10pt;">{l s='Cena celkem' pdf='true'}:   {$eetplus->toSmarty($data.total_paid_tax_incl)}  {l s='s DPH' pdf='true'}</span>  <br />
{/if}      
      
{if isset($data.tax_tab)}
<br /><br /><div style="font-size: 10pt;">{l s='Rozpis DPH' pdf='true'}</div>
<table cellpadding="5" cellspacing="0"> 
<tr><td style="font-size: 9pt;background-color:#e1e1e1;border:1px solid #a0a0a0;">{l s='Sazba' pdf='true'}</td><td style="font-size: 9pt;background-color:#e1e1e1;border:1px solid #a0a0a0;">{l s='Základ daně' pdf='true'}</td><td style="font-size: 9pt;background-color:#e1e1e1;border:1px solid #a0a0a0;">{l s='Daň' pdf='true'}</td></tr>
{foreach from=$data.tax_tab.dph item=sazba} 
{if isset($sazba.zaklad)}
<tr>
<td style="font-size: 9pt;background-color:#f1f1f1;border:1px solid #a0a0a0;">{$sazba.rate}%</td> 
<td style="font-size: 9pt;border:1px solid #a0a0a0;">{$eetplus->toSmarty($sazba.zaklad)}</td> 
<td style="font-size: 9pt;border:1px solid #a0a0a0;">{$eetplus->toSmarty($sazba.dan)}</td> 
</tr>
{/if}
{/foreach}
</table> 

{if isset($data.tax_tab.pouzit_zboz)}  
 <br />
 {foreach from=$data.tax_tab.pouzit_zboz item=pouzite} 
 {l s='Použité zboží sazba' mod='true'}   {$pouzite.rate} % {l s='cena celkem' mod='true'}: {$eetplus->toSmarty($pouzite.celkem)}<br />
 {/foreach}
 {/if}  
{/if}
 
  
                      
<br /> 
         
<div style="font-size: 10pt;font-weight:bold;">{l s='Pořadové číslo účtenky' pdf='true'}: {$data.poradove_cislo}</div>   
<div style="font-size: 10pt;font-weight:bold;">{$data.typkodu}: {$data.fik}  </div>  
<div style="font-weight: bold;font-size: 10pt;">{l s='BKP' pdf='true'}: {$data.bkp}   </div>    
<div style="font-weight: bold;font-size: 10pt;">{l s='Číslo objednávky' pdf='true'}: {$data.kod_objednavky}</div> 
{if isset($data.cisinv)}
    <div style="font-size: 10pt;font-weight:bold;">Číslo faktury: {$data.cisinv}  </div>  
{/if}     
<div style="font-weight: bold;font-size: 10pt;">{l s='Režim tržby' pdf='true'}: běžný<br />   
<div style="font-weight: bold;font-size: 10pt;">{l s='Celková částka' pdf='true'}: <b>{$eetplus->toSmarty($data.celk_trzba)}</div>   
        
            
  