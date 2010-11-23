{extends file="findExtends:common/base.tpl"}

{block name="body"}
<div id="news">
	<header class="news-header">
		{block name="news_header"}<h1>{$feed->title}</h1>{/block}
	</header>
	<ul class="news-items gloss">
		{block name="news_items_start"}
		<li class="selector arrow">
			<a href="feeds.php">Other Categories</a>
		</li>
		{/block}
		{block name="news_items"}
		{foreach $articles as $article}
		<li class="news-item article arrow"><a href="{$article->url}">
			<article{if !$article->image} class="no-image"{/if}>
				{if $article->image}
				<div class="image">
					<img src="{$article->image->getURL()}" />
				</div>
				{/if}
				<div class="title">
					{$article->getTitle()}
				</div>
				
				<div class="end"><!-- --></div>
			</article>
		</a></li>
		{/foreach}
		{/block}
		{block name="news_items_end"}
		{/block}
	</ul>
</div>
{/block}
