[{if $basketitem && $basketitem->getPersParams() && method_exists($basketitem, 'fcHasK3Configuration') && $basketitem->fcHasK3Configuration()}]
    <br />
    [{foreach from=$basketitem->fcGetK3Configuration() item=aParam key=sVar}]
        [{if $sVar == 'code'}]
            Code: [{$aParam}]<br/>
        [{elseif $sVar == 'app'}]
            App: [{$aParam}]<br/>
        [{elseif $sVar == 'variables'}]
            [{foreach from=$aParam item=variable}]
                [{$variable.label}] : [{$variable.value}]<br/>
            [{/foreach}]
        [{/if}]
    [{/foreach}]
[{else}]
    [{$smarty.block.parent}]
[{/if}]