{extends file="findExtends:common/base.tpl"}

{block name="body"}
	<h2>Choose a category</h1>

	<ul class="gloss">
		{foreach $feeds as $slug=>$feed}
		{if $slug == 'ucf-today'}
		<li class="arrow"><a href="../../{$slug}/">All Stories</a></li>
		{else}
		<li class="arrow"><a href="../../{$slug}/">{filter_title($feed->get_title())}</a></li>
		{/if}
		{/foreach}
	</ul>
{/block}