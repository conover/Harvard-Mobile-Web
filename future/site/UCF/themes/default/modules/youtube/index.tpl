{extends file="findExtends:common/base.tpl"}

{block name="body"}
	<div class="text">
		<h2>UCF on YouTube</h2>
		<div id="youtube">
			{if $videos}
				{foreach $videos as $video}
				<div class="block">
					{$video}
					<div class="clear">&nbsp;</div>
				</div>
				{/foreach}
			{else}
				<div class="block">
					<p>Error with youtube feed.</p>
					<p>Visit youtube directly at: <a href="http://m.youtube.com/ucf">http://m.youtube.com/ucf</a></p>
				</div>
			{/if}
		</div>
	</div>
	
	<ul class="gloss">
		<li class="arrow"><a href="http://m.youtube.com/ucf">More on YouTube</a></li>
	</ul>

{/block}