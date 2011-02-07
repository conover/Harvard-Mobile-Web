{extends file="findExtends:common/base.tpl"}

{block name="body"}
<div id="library">
	
	<ul class="gloss">
		<li class="search">
			<form method="get" action="">
				<div><input type="search" id="people-search-input" value="" placeholder="Search" name="q"></div>
				<input type="submit" value="Search">
			</form>
		</li>
	</ul>
	
	<div class="text">
		<div class="block">
			<h3>Library Stuff</h3>
			<p>{$foo}</p>
		</div>
	</div>
	
	<ul class="gloss">
		<li class="arrow"><a href="#">Link</a></li>
	</ul>

</div><!-- /library -->
{/block}