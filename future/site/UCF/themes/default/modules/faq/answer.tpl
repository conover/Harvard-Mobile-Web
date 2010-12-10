{extends file="findExtends:common/base.tpl"}

{block name="body"}
<div id="faqs">
	<ul class="gloss">
		<li class="arrow-back"><a href="../?q={urlencode($q)}">Return to Questions</a></li>
	</ul>
	<div class="text">
		<div class='block'>
			<h3>{$question}</h3>
			<div class="answer">
				{$answer}
			</div>
		</div>
	</div>
</div>
{/block}