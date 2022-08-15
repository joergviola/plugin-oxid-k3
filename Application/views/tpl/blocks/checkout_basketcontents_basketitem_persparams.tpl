[{if $basketitem && $basketitem->getPersParams() && method_exists($basketitem, 'fcHasK3Configuration') && $basketitem->fcHasK3Configuration()}]
<p class="persparamBox">
    <small>
        [{foreach key=sVar from=$basketitem->getPersParams() item=aParam name=persparams}]
            <input type="hidden" name="aproducts[[{$basketindex}]][persparam][[{$sVar}]]" value="[{$aParam}]">
        [{/foreach}]
        [{foreach from=$basketitem->fcGetK3Configuration() item=aParam key=sVar}]
            [{if $sVar == 'url'}]
                <a href="[{$aParam}]" target="_blank" rel="nofollow">[{oxmultilang ident="FCOBJECTCODEK3_BASKET_LINK"}]</a>
                <br/>
            [{elseif $sVar == 'variables'}]
                [{foreach from=$aParam item=variable}]
                    [{$variable.label}]: [{$variable.value}]<br/>
                [{/foreach}]
            [{/if}]
        [{/foreach}]
    </small>
</p>
[{else}]
    [{$smarty.block.parent}]
[{/if}]