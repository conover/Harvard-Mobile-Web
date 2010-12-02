{extends file="findExtends:common/base.tpl"}

{block name="body"}
<div>
	<h2>UCF Help</h2>
	<form action="" method="get"><div>
		<input type="search" placeholder="Ask a question" name="q" />
		<input type="submit" value="Search" />
	</div></form>
	{if $items}
	<ul id="text">
	{foreach $items as $item}
		<li class="block">
			<h3 class="question">{$item->getTitle()}</h3>
			<div class="answer">
				I am a robot
			</div>
		</li>
	{/foreach}
	</ol>
	{else if $q}
	<p>No help found for '{$q}'.</p>
	{else}
		
	{/if}
</div>
{/block}