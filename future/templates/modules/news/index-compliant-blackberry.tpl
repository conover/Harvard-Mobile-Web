{extends file="findExtends:modules/{$moduleID}/index.tpl"}

{block name="headersection"}
    {include file="findInclude:common/header-compliant-blackberry.tpl"}
{/block}

{block name="webkitSearchForm"}
{/block}

{block name="blackberrySearchForm"}
    {include file="findInclude:common/search-compliant-blackberry.tpl" 
    placeholder="Search "|cat:$moduleName}
{/block}
