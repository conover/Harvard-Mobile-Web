{extends file="findExtends:common/base.tpl"}


{block name="body"}
<div id="Flickr">
	<ul class="gloss tabs">
		<li><a class="grid" href="#">Grid</a></li>
		<li><a class="full" href="#">Full</a></li>
	</ul>
	<ul id="images">
		{foreach $items as $id=>$item}
		{$first    = $id == 0}
		{$image    = $item->get_enclosure()}
		{$original = $image->link}
		{if $image->thumbnails}
			{$thumbnail = $image->thumbnails[0]}
			{$src = $thumbnail}
		{else}
			{$thumbnail = ''}
			{$src = $original}
		{/if}
		<li {if $first}class="active"{/if} data-thumbnail="{$thumbnail}" data-original="{$original}" data-id="{$id}">
			<img src="{$src}" alt="{$item->get_title()}" >
		</li>
		{/foreach}
	</ul>
</div>
{/block}
