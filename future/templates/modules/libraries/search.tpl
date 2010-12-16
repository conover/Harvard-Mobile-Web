{include file="findInclude:common/header.tpl"}

{include file="findInclude:common/search.tpl" emphasized=false placeholder="Search" resultCount=$resultCount inlineSearchError=$searchError}

{include file="findInclude:modules/{$moduleID}/itemlist.tpl" items=$results}

{include file="findInclude:common/footer.tpl"}
