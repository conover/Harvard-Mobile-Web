{extends file="findExtends:common/base.tpl"}

{block name="body"}
<div id="faqs">
	<h2>FAQs{if $category}: {$category.name}{/if}</h2>
	<ul class="gloss">
		<li class="search">
			<form action="" method="get"><div>
				<input type="search" placeholder="Ask a question" name="q" id="faq-search-input" />
				<input type="submit" value="Search" />
			</div></form>
		</li>
	</ul>
	
	{if $items}
	<ul class="gloss">
		<li class="arrow"><a href="./categories.php">Select a different category to search </a></li>
	</ul>
	<ul class="gloss">
	{foreach $items as $item}
		<li class="arrow"><a href="answer.php?url={urlencode($item->getLink())}&amp;q={$q}">
			{$item->getTitle()}
		</a></li>
	{/foreach}
	</ul>
	{else if $q}
	<div class="text">
		<p class="block">No help found for '{$q}'.</p>
	</div>
	{else}
		
	{/if}
</div>
{/block}