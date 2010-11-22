{extends file="findExtends:common/base.tpl"}

{block name="body"}
	<header class="news-header">
		<h1><a href="feed.php">Return to {$feed->title}</a></h1>
	</header>
	<article class="content">
		<h1>{$article->getTitle()}</h1>
		<section>
			{$article->getContent()}
		</section>
		<a href="{$article->getLink()}">Read original</a>
	</article>
{/block}

