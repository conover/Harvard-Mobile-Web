{extends file="findExtends:common/base.tpl"}

{block name="body"}

<div id="Home">
<ul class="gloss">
{foreach $modules as $item}{* Add 'options' to map url *}
	{if stristr($item['title'], 'map')}
	{$item['opt'] = "options/"}
	{/if}
	<li class="Application arrow"><a href="{$item['url']}{$item['opt']|default:''}" class="{$item['class']|default:''}">{$item['title']}</a></li>
{/foreach}
</ul>

<ul class="gloss seperate">
	<li id="Customize" class="arrow"><a href="/customize/">Customize</a></li>
</ul>
</div>

{/block}
