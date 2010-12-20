{include file="findInclude:common/header.tpl" scalable=false}

{if $advancedSearch}
  {if $resultCount > 0}
    <div class="nonfocal">
      <p>
        {$resultCount} match{if $resultCount != 1}es{/if} found
        {if $resultCount > 2} 
          (<a href="#search">refine search</a>)
        {/if}
      </p>
    </div>
  {/if}

  {if $keywords || $title || $author}
    {include file="findInclude:modules/{$moduleID}/itemlist.tpl" items=$results}
  {/if}

  {include file="findInclude:modules/{$moduleID}/advanced_search.tpl"}
  
{else}
  {include file="findInclude:common/search.tpl" emphasized=false placeholder="Search" resultCount=$resultCount inlineSearchError=$searchError}
{/if}


{if !$advancedSearch}
  {include file="findInclude:modules/{$moduleID}/itemlist.tpl" items=$results}
{/if}

{include file="findInclude:common/footer.tpl"}
