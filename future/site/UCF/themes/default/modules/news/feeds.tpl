{extends file="findExtends:modules/$moduleID/index.tpl"}

{block name="news_header"}<h1>Select a feed</h1>{/block}

{block name="news_items_start"}{/block}

{block name="news_items"}
{foreach $feeds as $feed}
<li class="news-item feed arrow">
	<a href="../{$feed.url}">{$feed.title}</a>
</li>
{/foreach}
{/block}
