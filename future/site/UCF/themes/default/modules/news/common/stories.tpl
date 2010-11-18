<ul class="results">
	{if $previousUrl}
	<li class="non-story">
		<a href="{$previousUrl}">Previous stories</a>
	</li>
	{/if}

	{$ellipsisCount=0}
	{foreach $stories as $story}
	<li class="story">
		<a href="{$story['url']}">
			<div class="ellipsis" id="ellipsis_{$ellipsisCount++}">
				<div class="title">{$story["title"]}</div>
				{$story['description']}
			</div>
		</a>
	</li>
	{/foreach}

	{if $nextUrl}
	<li class="non-story">
	<a href="{$nextUrl}">More stories</a>
	</li>
	{/if}
</ul>
