{extends file="findExtends:modules/$moduleID/index.tpl"}

{block name="news_header"}<h1>Choose a category to read</h1>{/block}

{block name="news_items_start"}
<li class="selector">
	<a href="./index.php">Return to {$cfeed->title}</a>
</li>
{/block}

{block name="news_items"}
{foreach $feeds as $feed}
<li class="news-item feed">
	<a href="../{$feed.url}">{$feed.title}</a>
</li>
{/foreach}
{/block}
