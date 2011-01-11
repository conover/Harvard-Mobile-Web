{extends file="findExtends:common/base.tpl"}

{block name="body"}
<div id="Home">
	<ul>
	{foreach $modules as $item}{* Add 'options' to map url *}
		<li class="{$item['class']|default:''}"><a href="{$item['url']}{$item['opt']}">
			<img class="icon" src="{$item['img']}" alt="{$item['title']}">
			<span class="heading">{$item['title']}</span>
			<span class="description">{$item['description']}</span>
		</a></li>
	{/foreach}
	</ul>
</div>

{/block}
