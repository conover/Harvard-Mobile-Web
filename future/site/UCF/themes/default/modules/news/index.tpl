{extends file="findExtends:common/base.tpl"}

{block name="body"}
	{if filter_title($feed->get_title()) != "UCF Today"}
	<h2>{block name="news_header"}{filter_title($feed->get_title())}{/block}</h2>
	{/if}

	<ul class="gloss">
		<li class="arrow"><a href="feeds/">View Stories by Category</a></li>
	</ul>

	{if $articles}
		<ul class="articles">
			{foreach $articles as $article}
			<li>
				<article{if !$article->image} class="no-image"{/if}>
		
					<header>
						<a href="{$article->url}">{$article->get_title()}</a>
					</header>
					<section>
						{if $article->image}
						<a href="{$article->url}" class="img"><img src="{if $article->image->get_thumbnail()}{$article->image->get_thumbnail()}{else}{$article->image->get_link()}{/if}"></a>
						{elseif $article->imageAlt}
						<a href="{$article->url}" class="img">{$article->imageAlt}</a>
						{/if}
						<a href="{$article->url}">{$article->get_description()}</a>
					</section>
				</article>
			</li>
			{/foreach}
		</ul>

		{if $page.hasNext or $page.hasPrev}
		<ul class="gloss">
			{if $page.hasNext}
			<li class="arrow">
				<a href="?page={$page.current + 1}">Next page&hellip;</a>
			</li>
			{/if}
			{if $page.hasPrev}
			<li class="arrow-back">
				<a href="?page={$page.current - 1}">Previous page&hellip;</a>
			</li>
			{/if}
		</ul>
		{/if}
	{else}
		<p class="none">No articles found in this category.</p>
	{/if}

{/block}
