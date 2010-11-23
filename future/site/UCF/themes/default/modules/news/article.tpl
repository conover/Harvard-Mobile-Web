{extends file="findExtends:common/base.tpl"}

{block name="body"}
<div id="story">
	<article>
		<header class="news-header">
			<ul class="gloss">
				<li class="arrow-back"><a href="../index.php">Return to {$feed->title} Category</a></li>
				<li><a href="{$article->getLink()}">Read Original</a></li>
			</ul>
			<h1>{$article->getTitle()}</h1>
		</header>
		
		<section class="content">
		{$article->getContent()}
		<p>Written by {$article->getProperty('DC:CREATOR', 'Unknown')} on {$article->getPubDate()}</p>
		</section>
	</article>
</div>
{/block}

