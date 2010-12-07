{extends file="findExtends:common/base.tpl"}

{block name="body"}
<div id="faqs">
	<h2>FAQ Categories</h2>
	
	<ul class="gloss">
		<li class="arrow-back"><a href="../">Cancel</a></li>
	</ul>
	<ul class="gloss">
	{foreach $categories as $category}
		<li class="arrow"><a href="../../{$category.slug}/">
			{$category.name}
		</a></li>
	{/foreach}
	</ul>
</div>
{/block}