{extends file="findExtends:common/base.tpl"}

{block name="body"}
<div id="Home">
	<ul>
	{foreach $modules as $item}
		<li><a href="{$item['url']}{$item['opt']}">
			<img class="icon" src="{$item['img']}" alt="{$item['title']}">
			<span class="heading">{$item['fancy']}</span>
			<span class="description">{$item['description']}</span>
			<span class="end"><!-- --></span>
		</a></li>
	{/foreach}
	</ul>
</div>

{/block}
