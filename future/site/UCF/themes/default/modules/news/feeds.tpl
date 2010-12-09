{extends file="findExtends:common/base.tpl"}

{block name="body"}
	<h2>Choose a category</h1>

	<ul class="gloss">
		<li class="arrow-back"><a href="../">Return to {$cfeed->get_title()}</a></li>
		{foreach $feeds as $slug=>$feed}
		{if $slug == 'ucf-today'}
		<li class="arrow"><a href="../../{$slug}/">All UCF Stories</a></li>
		{else}
		<li class="arrow"><a href="../../{$slug}/">{$feed->get_title()}</a></li>
		{/if}
		{/foreach}
	</ul>
{/block}