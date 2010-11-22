{extends file="findExtends:common/base.tpl"}

{block name="body"}
	<header class="news-header">
		{block name="news_header"}<h1>News</h1>{/block}
	</header>
	<ul class="news-items gloss">
		{block name="news_items_start"}{/block}
		{block name="news_items"}
		{foreach $feeds as $feed}
		<li class="news-item feed arrow">
			<a href="{$feed.url}">{$feed.title}</a>
		</li>
		{/foreach}
		{/block}
		{block name="news_items_end"}{/block}
	</ul>
{/block}