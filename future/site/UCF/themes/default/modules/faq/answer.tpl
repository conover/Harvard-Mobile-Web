{extends file="findExtends:common/base.tpl"}

{block name="body"}
<ul class="gloss">
	<li class="arrow-back"><a href="./?q={urlencode($q)}">Return to Questions</a></li>
</ul>
<div class="text">
	<div class='block'>
		{$content}
	</div>
</div>
{/block}