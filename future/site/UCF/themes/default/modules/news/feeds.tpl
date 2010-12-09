{extends file="findExtends:common/base.tpl"}

{block name="body"}
	<h2>Choose a category to read</h1>

	<ul class="gloss">
		<li class="arrow-back"><a href="../">Return to {$cfeed->get_title()}</a></li>
		{foreach $feeds as $slug=>$feed}
		<li class="arrow"><a href="../../{$slug}/">{$feed->get_title()}</a></li>
		{/foreach}
	</ul>
{/block}