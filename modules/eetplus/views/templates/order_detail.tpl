<div class="row" id="start_eet">

        <div class="col-lg-12">
            <form class="container-command-top-spacing" action="index.php?controller=AdminOrders&amp;vieworder&amp;token={$smarty.get.token|escape:'html':'UTF-8'}&amp;id_order={$order->id|intval}" method="post" >
                <input type="hidden" name="id_order" value="{$order->id}" />
                
                <div class="panel">
                    <div class="panel-heading">
                        <i class="icon-shopping-cart"></i>
                        {l s='EET'} <span class="badge"></span>
                        <input type='checkbox' {if isset($haseet)}{$haseet}{/if} onClick="$('#show_eet').toggle()"/>
                    </div>
          
                    <div class="table-responsive" id="show_eet" style="display:{if isset($haseet)}block{else}none{/if}">
                        <table class="table" id="orderProducts">
                            <thead>
                                <tr>
                                 <th colspan=2>&nbsp;</th>
                                 <th colspan=6>{l s='Rozpis DPH (Kč)'}</th>
                                  <th colspan=3>{l s='Použité zboží cena celkem (Kč)'}</th>
                                </tr>
                                <tr>
                                    <th><span class="title_box ">{l s='EET ID'}</span></th>
                                     <th><span class="title_box ">{l s='Celk. tržba (Kč)'}</span></th> 
                                     {if isset($use_tax) && $use_tax} 
                                     <th><span class="title_box ">{l s='základ '} {$sazby.zakladni}%</span></th> 
                                     <th><span class="title_box ">{l s='daň '}  {$sazby.zakladni}%</span></th> 
                                     <th><span class="title_box ">{l s='základ '}  {$sazby.snizena1}%</span></th> 
                                     <th><span class="title_box ">{l s='daň '} {$sazby.snizena1}%</span></th> 
                                     <th><span class="title_box ">{l s='základ '} {$sazby.snizena2}%</span></th> 
                                     <th><span class="title_box ">{l s='daň '} {$sazby.snizena2}%</span></th> 
                                     
                                     <th><span class="title_box ">{$sazby.zakladni}%</span></th> 
                                     <th><span class="title_box "> {$sazby.snizena1}%</span></th> 
                                     <th><span class="title_box "> {$sazby.snizena2}%</span></th> 
                                    {/if} 
                                     {if isset($cerpani)}
                                         <th><span class="title_box ">{l s='Urč. čerp. (Kč)'} </span></th> 
                                         <th><span class="title_box ">{l s='Čerp. zůčt. (Kč)'} </span></th> 
                                         <th><span class="title_box ">{l s='Cest sl. (Kč)'} </span></th> 
                                     {/if}
                                     {if isset($nepodl)}
                                         <th><span class="title_box ">{l s='Osvob. (Kč)'} </span></th> 
                                     {/if}
                                       
                                    
                                     
                                </tr>
                            </thead>
                            <tbody>
                        {if isset($history)} 
                            {foreach from=$history  key=id_eetplus item=polozka}
                             <tr>
                             <td><a href="{$linkeet}{$id_eetplus}"/>{$id_eetplus}</a></td><td>{$eetplus->toSmarty($polozka.celk_trzba, false)}</td>
                            
                             {if isset($use_tax) && $use_tax} 
                                 {if isset($polozka.dph.1.zaklad)}
                                 <td>{$eetplus->toSmarty($polozka.dph.1.zaklad, false)}</td>
                                 <td>{$eetplus->toSmarty($polozka.dph.1.dan, false)}</td>
                                 {else}
                                  <td>0</td> <td>0</td>
                                 {/if}
                                 
                                 {if isset($polozka.dph.2.zaklad)}
                                 <td>{$eetplus->toSmarty($polozka.dph.2.zaklad, false)}</td>
                                 <td>{$eetplus->toSmarty($polozka.dph.2.dan, false)}</td>
                                 {else}
                                  <td>0</td> <td>0</td>
                                 {/if}
                                 
                                 {if isset($polozka.dph.3.zaklad)}
                                 <td>{$eetplus->toSmarty($polozka.dph.3.zaklad, false)}</td>
                                 <td>{$eetplus->toSmarty($polozka.dph.3.dan, false)}</td>
                                 {else}
                                  <td>0</td> <td>0</td>
                                 {/if}
                                 
                                 {if isset($polozka.pouzit_zboz.1.celkem)}
                                 <td>{$eetplus->toSmarty($polozka.pouzit_zboz.1.celkem, false)}</td>
                                 {else}
                                  <td>0</td>
                                 {/if}
                                 
                                 {if isset($polozka.pouzit_zboz.2.celkem)}
                                 <td>{$eetplus->toSmarty($polozka.pouzit_zboz.2.celkem, false)}</td>
                                 {else}
                                  <td>0</td>
                                 {/if}
                                 
                                 {if isset($polozka.pouzit_zboz.3.celkem)}
                                 <td>{$eetplus->toSmarty($polozka.pouzit_zboz.3.celkem, false)}</td>
                                 {else}
                                  <td>0</td>
                                 {/if}
                             {/if}
                             
                             {if isset($cerpani)}
                                {if isset($polozka.eet_extra.urceno_cerp_zuct)}
                                      <td>{$eetplus->toSmarty($polozka.eet_extra.urceno_cerp_zuct, false)}</td>
                                {else} 
                                       <td>0</td>
                                {/if}
                                {if isset($polozka.eet_extra.cerp_zuct)}
                                      <td>{$eetplus->toSmarty($polozka.eet_extra.cerp_zuct, false)}</td>
                                {else} 
                                       <td>0</td>
                                {/if}
                                {if isset($polozka.eet_extra.cest_sluz)}
                                        <td>{$eetplus->toSmarty($polozka.eet_extra.cest_sluz, false)}</td>
                                {else} 
                                        <td>0</td>
                                {/if}
                              
                            {/if}
                            {if isset($nepodl)}
                               {if isset($polozka.eet_extra.zakl_nepodl_dph)}
                                        <td>{$eetplus->toSmarty($polozka.eet_extra.zakl_nepodl_dph, false)}</td>
                                {else} 
                                        <td>0</td>
                                {/if}
                            {/if}
                                       
                             
                             </tr>
                            {/foreach}
                         {/if}   {* history *}
                         
                           {* bilance *}
                         {if isset($bilance)}
                            <tr>
                             <td style="background-color:#dddddd;">{l s='Součet:'}</td>
                             <td style="background-color:#dddddd;">{$eetplus->toSmarty($bilance.celk_trzba, false)}</td>
                        
                             {* dph sazba 1 *}
                              <td style="background-color:#dddddd;">{$eetplus->toSmarty($bilance.dph.1.zaklad, false)}</td>
                              <td style="background-color:#dddddd;">{$eetplus->toSmarty($bilance.dph.1.dan, false)}</td>
                             
                             {* dph sazba 2 *} 
                             <td style="background-color:#dddddd;">{$eetplus->toSmarty($bilance.dph.2.zaklad, false)}</td>
                             <td style="background-color:#dddddd;">{$eetplus->toSmarty($bilance.dph.2.dan, false)}</td>
                            
                            {* dph sazba 3 *}  
                              <td style="background-color:#dddddd;">{$eetplus->toSmarty($bilance.dph.3.zaklad, false)}</td> 
                              <td style="background-color:#dddddd;">{$eetplus->toSmarty($bilance.dph.3.dan, false)}</td>
                             
                             {* pouzite sazba 1 *}  
                             
                            <td style="background-color:#dddddd;">{if isset($bilance.pouzit_zboz.1.celkem)}{$eetplus->toSmarty($bilance.pouzit_zboz.1.celkem, false)}{else}0{/if}</td>
                             
                             {* pouzite sazba 2 *}  
                            <td style="background-color:#dddddd;">{if isset($bilance.pouzit_zboz.2.celkem)}{$eetplus->toSmarty($bilance.pouzit_zboz.2.celkem, false)}{else}0{/if}</td>
                             
                             {* pouzite sazba 3 *}  
                             <td style="background-color:#dddddd;">{if isset($bilance.pouzit_zboz.3.celkem)}{$eetplus->toSmarty($bilance.pouzit_zboz.3.celkem, false)}{else}0{/if}</td>
                            
                            
                            {if isset($cerpani)}
                            <td style="background-color:#dddddd;">{if isset($bilance.eet_extra.urceno_cerp_zuct)}{$eetplus->toSmarty($bilance.eet_extra.urceno_cerp_zuct, false)}{else}0{/if}</td>
                                                  
                             <td style="background-color:#dddddd;">{if isset($bilance.eet_extra.cerp_zuct)}{$eetplus->toSmarty($bilance.eet_extra.cerp_zuct, false)}{else}0{/if}</td>
                             
                             <td style="background-color:#dddddd;">{if isset($bilance.eet_extra.cest_sluz)}{$eetplus->toSmarty($bilance.eet_extra.cest_sluz, false)}{else}0{/if}</td>
                            {/if}
                            
                            {if isset($nepodl)}
                             <td style="background-color:#dddddd;">{if isset($bilance.eet_extra.zakl_nepodl_dph)}{$eetplus->toSmarty($bilance.eet_extra.zakl_nepodl_dph, false)}{else}0{/if}</td>
                            {/if}
                             </tr>    
                         {/if} 
                         {* bilance *}
                         
                         
                          {* rozdil *}
                            <tr>
                          
                             <td>{l s='Upravit:'}</td><td><input type='text' size=5 name='celk_trzba' /></td>
                        
                             {* dph sazba 1 *}
          
                             <td><input type='text' size=5 name='dph[1][zaklad]' /></td>
                             
                            
                            <td><input type='text' size=5 name='dph[1][dan]'  /></td>
                             
                         
                             <td><input type='text' size=5 name='dph[2][zaklad]'  /></td> 
                        
                            <td><input type='text' size=5 name='dph[2][dan]'  /></td>
                            
                           
                             <td><input type='text' size=5 name='dph[3][zaklad]' /></td> 
                             
                            
                            <td><input type='text' size=5 name='dph[3][dan]'  /></td>
                             
                          
                             <td><input type='text' size=5 name='pouzit_zboz[1][celkem]' /></td>  
                             
                             {* pouzite sazba 2 *}  
                           
                             <td><input type='text' size=5 name='pouzit_zboz[2][celkem]'  /></td>  
                             
                             {* pouzite sazba 3 *}  
                            
                             <td><input type='text' size=5 name='pouzit_zboz[3][celkem]' /></td>  
                            
                            
                              {if isset($cerpani)}
                              
                                 <td><input type='text' size=5 name='eet_extra[urceno_cerp_zuct]'  /></td> 
                    
                                 <td><input type='text' size=5 name='eet_extra[cerp_zuct]'  /></td> 
                              
                                
                                 <td><input type='text' size=5 name='eet_extra[cest_sluz]'  /></td> 
                             {/if}
                            
                            {if isset($nepodl)}
                              
                                <td><input type='text' size=5 name='eet_extra[zakl_nepodl_dph]' /></td> 
                            {/if}
                            
                            
                            
                            
                             </tr>    
                         {* rozdil *}
                            </tbody>
                        </table>
                      
                            <br />   
                        <input type='submit' class="btn btn-default" value="{l s='Odeslat úpravy EET'}"  name="submitEet"> 
                        {l s='Ujistěte se prosím  zda  opravdu existují důvody opravu odeslat!'}
                        {l s='Čásky se přičítají k existujícím. Pokud chcete tržbu snížit, napište částku se záporným znaménkem'}
                         <br /><input type='checkbox'   value="1"  name="chckClearEet"> {l s='Vynulovat bilanci'} 
                    </div>
                    
             
                    <div class="clear">&nbsp;</div>
                 
                </div>
            </form>
        </div>
    </div>
 <script language="JavaScript">
        <!--
         function fbox(id_order, id_order_slip) {
          var url =   "{$base_url}/fbox.php?id_order=" + id_order + "&id_order_slip="+id_order_slip;
          
       
        $.fancybox({
        type: "iframe",
        href: url,
        "width": 600,
        "height": 600,
        });
        }
 
   //-->
</script>       