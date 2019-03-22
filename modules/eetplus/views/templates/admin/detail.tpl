<div class="panel">


    <div class="panel-heading">
        <i class="icon-money"></i>
        EET pořadové číslo: <span class="badge">{if isset($id_eetplus)}{$id_eetplus}{/if}</span>
    </div>
    <a href="{$linkorder}"/>{l s='Přejít do objednávky'} {$order->id}</a>
    {if isset($sandbox) && $sandbox == 1}
        <h2>{l s='Testovací režim !!'}</h2>
    {/if}

    <div class="table-responsive">
        <table class="table">
            <tr>
                <td class="col-lg-3">FIK</td>
                <td class="col-lg-9">{if isset($eet->fik)}{$eet->fik}{/if}</td>
            </tr>
            <tr>
                <td>PKP</td>
                <td>
                    <textarea rows="4" cols=50>{if isset($eet->pkp)}{$eet->pkp}{/if}</textarea></td>
            </tr>
            <tr>
                <td>BKP</td>
                <td>{if isset($eet->bkp)}{$eet->bkp}{/if}</td>
            </tr>
            <tr>
                <td>akce</td>
                <td>{if isset($eet->action)}{$eet->action}{/if}</td>
            </tr>
            <tr>
                <td>EET datum</td>
                <td>{if isset($data.datum)}{dateFormat date=$data.datum full=true}{/if}</td>
            </tr>
            <tr>
                <td>Objednávka číslo</td>
                <td>{if isset($eet->id_order)}{$eet->id_order}{/if}</td>
            </tr>
            <tr>
                <td>Objednávka reference</td>
                <td>{if isset($order->reference)}{$order->reference}{/if}</td>
            </tr>
            <tr>
                <td>Objednávka datum vytvoření</td>
                <td>{if isset($order->date_add)}{dateFormat date=$order->date_add full=true}{/if}</td>
            </tr>
            <tr>
                <td>Účtenka odeslána na email / datum</td>
                <td>{if isset($data.mailto)}{$data.mailto} / {$data.maildate}{/if}</td>
            </tr>
            <tr>
                <td>Objednávka částka</td>
                <td>{if isset($data.total_paid)}{$eetplus->toSmarty($data.total_paid, false)} {$data.currency}{/if}</td>
            </tr>
            <tr>
                <td>EET odeslaná tržba</td>
                <td><b>{if isset($data.castka)}{$eetplus->toSmarty($data.castka)} {/if}</b></td>
            </tr>


        </table>
    </div>
</div>
<div class="panel">
    <br/>
    {$data.ceny}

    {if $order->conversion_rate == 1}
    {else}
        <div>
            <br/> <br/>
            Objednávka byla vytvořena v měně {$data.currency} a při zaslání tržby
            přepočtena na Kč kurzem {$kurz}
            <br/>
        </div>
    {/if}


</div>
<div class="panel">


    <div class="panel-heading">
        <i class="icon-money"></i>
        PDF: <span class="badge"></span>
    </div>

    <div class="form-group" style="margin-bottom:0px;">
        <form id="formPdf" method="post"
              action="index.php?controller=AdminOrdersEetplus&amp;vieworder&amp;id_order=42&amp;token={$smarty.get.token}&amp;id_eetplus={$id_eetplus}">


            <input type='hidden' name='id_order' value='{$order->id}'/>
            <input type='hidden' name='id_eetplus' value='{$id_eetplus}'/>

    </div>
    <button type="submit" name="submitPdf" class="btn btn-default" style="clear:left;display:block;">
        <i class="icon-check"></i>
        {l s='Stáhnout'}
    </button>
    </form>
    <br/> <br/>
    <a href="https://adisdpr.mfcr.cz/adistc/adis/idpr_pub/eet/eet_sluzby.faces" target="_blank">Vstup do portálu EET</a>

</div>


<div class="panel">


    <div class="panel-heading">
        <i class="icon-money"></i>
        Email: <span class="badge"></span>
    </div>

    <div class="form-group" style="margin-bottom:0px;">
        <form id="formEmail" method="post"
              action="index.php?controller=AdminOrdersEetplus&amp;vieworder&amp;id_order=42&amp;token={$smarty.get.token}&amp;id_eetplus={$id_eetplus}">
            <label class="control-label col-lg-3">Znovu odešle původní účtenku.</label>
            <div class="col-lg-6">


                <input type='hidden' name='id_order' value='{$order->id}'/>
                <input type='hidden' name='id_eetplus' value='{$id_eetplus}'/>

                <input type="text" name="emailto" value="{if isset($data.mailto)}{$data.mailto}{/if}" size="30"
                       style="width:auto;"/>

            </div>
            <button type="submit" name="submitEmail" class="btn btn-default" style="clear:left;display:block;">
                <i class="icon-check"></i>
                {l s='Odeslat'}
            </button>
        </form>

    </div>
</div>

   
 
      
