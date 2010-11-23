{extends file="findExtends:common/base.tpl"}

{block name="body"}
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
		<li class="news-item article arrow">
			<a href="{$article->url}">{$article->getTitle()}</a>
		</li>
		{/foreach}
		{/block}
		{block name="news_items_end"}{/block}
	</ul>
{/block}
