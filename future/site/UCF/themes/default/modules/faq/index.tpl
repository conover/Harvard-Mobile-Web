{extends file="findExtends:common/base.tpl"}

{block name="body"}
<div id="faqs">
	<h2>Search FAQs</h2>
	<ul class="gloss">
		<li class="search">
			<form action="" method="get">
				<div><input type="search" placeholder="Ask a question" name="q" id="faq-search-input" /></div>
				<input type="submit" value="Search" /></form>
		</li>
	</ul>

	<ul class="gloss">
		<li class="arrow"><a href="categories/">FAQ Categories</a></li>
	</ul>
	
	{if ($items and ($category.id != 0)) or ($items and $category.id == 0 and $q)}
	<h3>{$category.name}</h3>
	<ul class="gloss">
	{foreach $items as $id=>$item}
		<li class="arrow"><a href="answer/?url={urlencode($item->get_link())}&amp;q={$q}">
			{$item->get_title()}
		</a></li>
	{/foreach}
	</ul>
	{else if $q}
	<div class="text">
		<p class="block">No help found for '{$q}'.</p>
	</div>
	{/if}
</div>
{/block}