{extends file="findExtends:modules/$moduleID/index.tpl"}

{block name="news_header"}
<h1>{$feed->title}</h1>
{/block}

{block name="news_items"}
	{foreach $articles as $article}
	<li class="news-item article arrow">
		<a href="{$article->url}">{$article->getTitle()}</a>
	</li>
	{/foreach}
{/block}