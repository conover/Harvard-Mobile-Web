{extends file="findExtends:common/base.tpl"}

{block name="body"}
<ul class="gloss">
	<li class="arrow-back"><a href="../index.php">Return to {filter_title($feed->get_title())} Category</a></li>
	<li class="arrow"><a href="{$article->get_link()}">Read Original</a></li>
</ul>
<div class="pad">
	<article class="block">
		<h1>{$article->get_title()}</h1>
		<section class="content">
			{strip_img_dimensions($article->get_content())}
			<p>Written by {$article->get_author()->get_name()} on {$article->get_date()}</p>
		</section>
	</article>
</div>
{/block}

