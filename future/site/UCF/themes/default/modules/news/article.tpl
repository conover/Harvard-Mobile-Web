{extends file="findExtends:common/base.tpl"}

{block name="body"}
<ul class="gloss">
	<li class="arrow-back"><a href="../index.php">Back</a></li>
</ul>
<div class="pad">
	<article class="block">
		<h1>{$article->get_title()}</h1>
		<section class="content">
			{strip_img_dimensions($article->get_content())}
			<p>Posted by {$article->get_author()->get_name()} on {$article->get_date()}</p>
		</section>
		<a class="original" href="{$article->get_link()}">View this story on today.ucf.edu</a>
	</article>
</div>
{/block}

