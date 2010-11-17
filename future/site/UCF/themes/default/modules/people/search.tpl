{extends file="findExtends:modules/{$moduleID}/base.tpl"}

{block name="body"}
<form action="{$searchURL}" method="get"><div>
	<input type="search" name="{$queryName}" id="people-search-input" />
	<input type="submit" />
</div></form>

{if $query}
<p>Search results for '{$query}':</p>
{if $listing}
<ul id="results">
	{foreach $listing as $result}
	<li>
		{include file="findInclude:modules/{$moduleID}/result.tpl" result=$result}
	</li>
	{/foreach}
</ul>
{else}
<p class="no-result">Sorry, we found no results for '{$query}'.</p>
{/if}
{/if}

{/block}

