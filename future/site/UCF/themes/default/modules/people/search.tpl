{extends file="findExtends:common/base.tpl"}

{block name="body"}
<div id="people-search">
	<h2>Search for people at UCF</h2>
	<form action="{$searchURL}" method="get"><div>
		<input type="search" name="{$queryName}" id="people-search-input" />
		<input type="submit" value="Search" />
	</div></form>
	
	{if $query}
	<p>Found {count($listing)} results for '{$query}':</p>
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
</div>
{/block}

