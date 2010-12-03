{extends file="findExtends:common/base.tpl"}

{block name="body"}
	<h2 class="c">Choose a category to read</h1>

	<ul class="gloss">
		<li class="arrow-back"><a href="../">Return to {$cfeed->title}</a></li>
		{foreach $feeds as $feed}
		<li class="arrow"><a href="../../{$feed.url}">{$feed.title}</a></li>
		{/foreach}
	</ul>
{/block}