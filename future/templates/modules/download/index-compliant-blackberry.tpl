{extends file="findExtends:modules/{$moduleID}/index.tpl"}

{block name="headersection"}
    {include file="findInclude:common/header-compliant-blackberry.tpl"}
{/block}

{block name="searchsection"}
  {include file="findInclude:common/search-compliant-blackberry.tpl" placeholder="Search "|cat:$SITE_NAME}
{/block}
