{extends file="findExtends:common/base.tpl"}

{block name="body"}
<ul class="gloss">
{foreach $modules as $item}{* Add 'options' to map url *}
	{if stristr($item['title'], 'map')}
	{$item['opt'] = "options/"}
	{/if}
	<li class="Application arrow"><a href="{$item['url']}{$item['opt']|default:''}" class="{$item['class']|default:''}">{$item['title']}</a></li>
	{if stristr($item['title'], 'events')}
	<li class="Application arrow"><a href="http://m.youtube.com/ucf">UCF on YouTube</a></li>
	{/if}
{/foreach}
</ul>

{/block}
