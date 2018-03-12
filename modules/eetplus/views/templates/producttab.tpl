 <div class="row" id="start_eet">
        <div class="col-lg-12">
            <form class="container-command-top-spacing"   method="post" >
                   
                <div class="panel">
                    <div class="panel-heading">
                        <i class="icon-shopping-cart"></i>
                        {l s='EET'} <span class="badge"></span>
                    </div>
              
                    
                    <div class="checkbox">
                        <label for="eet_cerp">
                            <input type="checkbox" name="eet_cerp" id="eet_cerp" value="1" {if $eet_cerp}checked="checked"{/if} >
                            {l s='Následné čerpání'}</label>
                    </div>
                    <div class="checkbox">
                        <label for="eet_cest">
                            <input type="checkbox" name="eet_cest" id="eet_cest" value="1" {if $eet_cest}checked="checked"{/if} >
                            {l s='Cestovní služba'}</label>
                    </div>
                     
            
             
                     <br /><br />
                    <input type="submit" name="submitAddproductAndStay" value="{l s='Uložit a zůstat' mod='eetplus'}" /> 
                       </div>
              </form>
        </div>
 </div>