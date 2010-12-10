{extends file="findExtends:common/base.tpl"}

{block name="body"}
	<div class="text">
		<h2>UCF on YouTube</h2>
		<div id="youtube">
			{$content}
		</div>
	</div>
	
	<ul class="gloss">
		<li class="arrow"><a href="http://m.youtube.com/ucf">More {$count} on YouTube</a></li>
	</ul>

{/block}