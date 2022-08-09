[{if $basketitem && $basketitem->getPersParams() && method_exists($basketitem, 'fcHasK3Configuration') && $basketitem->fcHasK3Configuration()}]
    <br />
    [{foreach key=sVar from=$basketitem->getPersParams() item=aParam name=persparams}]
        <input type="hidden" name="aproducts[[{$basketindex}]][persparam][[{$sVar}]]" value="[{$aParam}]">
    [{/foreach}]
    [{foreach from=$basketitem->fcGetK3Configuration() item=aParam key=sVar}]
        [{if $sVar == 'id'}]
            Konfiguration: [{$aParam}]<br/>
        [{elseif $sVar == 'variables'}]
            [{foreach from=$aParam item=variable}]
                [{$variable.id}] [{$variable.label}] [{$variable.value}]<br/>
            [{/foreach}]
        [{/if}]
    [{/foreach}]
[{else}]
    [{$smarty.block.parent}]
[{/if}]