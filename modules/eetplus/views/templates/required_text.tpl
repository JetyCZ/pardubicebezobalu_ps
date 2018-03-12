{if isset($clear)}<div style="clear:both"></div>{/if}
{if isset($eettitle)}
<div  class="block">
     <p class='title_block' style="color:{$barva}">{l s='EET' mod='eetplus'}</p> 
     <div class="block_content">
     {l s='Podle zákona o evidenci tržeb je prodávající povinen vystavit kupujícímu účtenku. Zároveň je povinen zaevidovat přijatou tržbu u správce daně online; v případě technického výpadku pak nejpozději do 48 hodin.' mod='eetplus'}
     {l s='Tato povinnost se v současné době vztahuje pouze na některé typy plateb, zejména platby on-line převodem.'}
     </div>
</div>
{else}
<div style="color:{$barva}">
{l s='Podle zákona o evidenci tržeb je prodávající povinen vystavit kupujícímu účtenku. Zároveň je povinen zaevidovat přijatou tržbu u správce daně online; v případě technického výpadku pak nejpozději do 48 hodin.' mod='eetplus'}
</div>
{/if}