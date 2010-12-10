{extends file="findExtends:common/base.tpl"}

{block name="body"}
	<h2>Campus Map</h2>
		<ul class="gloss">
			<li class="search">
				<form action="{if $directions}../directions/{else}../search/{/if}" method="get">
					<div><input type="search" placeholder="search" results="0" name="q" {if $search_q}value="{$search_q}"{/if}></div>
					<input type="submit" value="Search" />
				</form>
			</li>
			
			{if $search_q}
			
				{if empty($results)}
					</ul>
					<p id="no-results">No results</p>
				{else}
						{foreach $results as $item}
						<li class="arrow"><a href="/map/{if $directions}me+{/if}location/{$item->number}">{$item->name}</a></li>
						{/foreach}
					</ul>
				{/if}
					
				<ul class="gloss">
					<li class="arrow-back"><a href="/map/options/">Back to Options</a></li>
					{if $directions}
					<li class="arrow-back"><a href="/map/me">Back Campus Map</a></li>
					{else}
					<li class="arrow-back"><a href="/map/">View UCF Campus Map</a></li>
					{/if}
				</ul>
			
			{else}
			
				<li class="arrow"><a id="back" href="/map/me">Where am I?</a></li>
				<li class="arrow"><a id="back" href="/map/traffic/">View Traffic</a></li>
				<li class="arrow"><a id="back" href="/map/">View UCF Campus Map</a></li>
			</ul>
			
			{/if}

{/block}
			