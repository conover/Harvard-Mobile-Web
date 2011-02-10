{extends file="findExtends:common/base.tpl"}

{block name="body"}
<ul class="gloss">
	<li class="arrow-back"><a href="../index.php">Back</a></li>
</ul>
<div class="pad">
	<article class="block">
		<header>
			<h1>{$article->get_title()}</h1>
			<div class="author">Posted by {$article->get_author()->get_name()}</div>
			<div class="date">{$article->get_date()}</div>
		</header>
		<section class="content">
			{strip_img_dimensions($article->get_content())}
		</section>
		<footer>
			<a class="original" href="{$article->get_link()}">View this story on today.ucf.edu</a>
		</footer>
	</article>
</div>
{/block}

