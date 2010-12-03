{extends file="findExtends:common/base.tpl"}

{block name="body"}

	<h2 class="c">{block name="news_header"}{$feed->title}{/block}</h2>

	<ul class="gloss">
		<li class="arrow"><a href="feeds/">Other Categories</a></li>
	</ul>

	{if $articles}
		<ul class="articles">
			{foreach $articles as $article}
			<li>
				<article{if !$article->image} class="no-image"{/if}>
		
					<header>
						<a href="{$article->url}">{$article->getTitle()}</a>
					</header>
					<section>
						{if $article->image}
						<a href="{$article->url}" class="img"><img src="{$article->image->getURL()}"></a>
						{/if}
						<a href="{$article->url}">{$article->getDescription()}</a>
					</section>	
					<footer>
				
					</footer>
		
		
		
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
		<p>No articles found in this category.</p>
	{/if}

{/block}
