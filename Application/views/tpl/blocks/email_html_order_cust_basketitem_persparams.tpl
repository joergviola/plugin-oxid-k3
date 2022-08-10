[{if $basketitem && $basketitem->getPersParams() && method_exists($basketitem, 'fcHasK3Configuration') && $basketitem->fcHasK3Configuration()}]
    <br />
    [{foreach from=$basketitem->fcGetK3Configuration() item=aParam key=sVar}]
        [{if $sVar == 'configurationId'}]
            Konfiguration-ID: [{$aParam}]<br/>
        [{elseif $sVar == 'appCode'}]
            AppCode: [{$aParam}]<br/>
        [{elseif $sVar == 'variables'}]
            [{foreach from=$aParam item=variable}]
                [{$variable.id}] [{$variable.label}] [{$variable.value}]<br/>
            [{/foreach}]
        [{/if}]
    [{/foreach}]
[{else}]
    [{$smarty.block.parent}]
[{/if}]