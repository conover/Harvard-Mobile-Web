{extends file="findExtends:common/base.tpl"}

{block name="body"}
<div id="people-search">
	<h2>UCF Directory</h2>
	<ul class="gloss">
		<li class="search">
			<form action="" method="get">
				<div><input type="search" name="{$queryName}" placeholder="Search" value="{$query}" id="people-search-input"></div>
				<input type="submit" value="Search">
			</form>
		</li>
	</ul>
	
	
	{if $query}
		{if $listing}
		<div class="text">
			<div class="block">
				<h3>Found {count($listing)} result{if count($listing) != 1}s{/if} matching '{$query}':</h3>
			</div>
		</div>
		<ul class="articles">
			{foreach $listing as $result}
			<li>
				{include file="findInclude:modules/{$moduleID}/result.tpl" result=$result}
			</li>
			{/foreach}
		</ul>
		{else}
		<div class="text">
			<div class="block">
				<h3>Results</h3>
				<p>No results for '{$query}'</p>
			</div>
		</div>
		{/if}
	{else}
	<div class="text">
		<div class="block">
			<h3>Searching</h3>
			<p>Search by: first and/or last name, organization name, phone number (ex. 407-555-5555), e-mail address, or location.</p>
		</div>
	</div>
	{/if}
</div>
{/block}

