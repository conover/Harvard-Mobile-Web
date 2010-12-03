{extends file="findExtends:common/base.tpl"}

{block name="body"}
<div id="people-search">
	<h2>Search for people at UCF</h2>
	<ul class="gloss">
		<li class="search">
			<form action="{$searchURL}" method="get">
				<div><input type="search" name="{$queryName}" id="people-search-input" /></div>
				<input type="submit" value="Search" />
			</form>
		</li>
	</ul>
	
	
	{if $query}
		{if $listing}
		<h3>Found {count($listing)} results for '{$query}':</h3>
		<ul class="articles">
			{foreach $listing as $result}
			<li>
				{include file="findInclude:modules/{$moduleID}/result.tpl" result=$result}
			</li>
			{/foreach}
		</ul>
		{else}
		<h3>No results for '{$query}'</h3>
		{/if}
	{/if}
</div>
{/block}

