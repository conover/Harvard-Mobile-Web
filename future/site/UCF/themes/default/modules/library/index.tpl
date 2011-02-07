{extends file="findExtends:common/base.tpl"}

{block name="additionalHeadTags"}
	<!--
	<link rel="stylesheet" type="text/css" href="http://library.ucf.edu/Web/CSS/Main.css">
	<link rel="stylesheet" type="text/css" href="http://library.ucf.edu/Web/CSS/Advanced.asp?section=about">
	-->
	<link rel="stylesheet" type="text/css" href="http://library.ucf.edu/Web/CSS/Maps.css">
	<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
	
	<script src="http://library.ucf.edu/Web/JS/mootools-1.2.5.js" type="text/javascript"></script>
	<script src="http://library.ucf.edu/Web/JS/clientcide-trunk.js" type="text/javascript"></script>
	<!--
	<script src="/Web/JS/class.MavSelectBox.js" type="text/javascript"></script>
	-->
{/block}

{block name="body"}
<div id="library">
	<script type="text/javascript" src="http://library.ucf.edu/Web/JS/Main.js"></script>
	<script type="text/javascript" src="http://library.ucf.edu/Web/JS/Maps.js"></script>
	
	<div id="FullMapCanvas" class="GoogleMap">
		The UCF Libraries map is attempting to load. (This map requires JavaScript.)
	</div>
	<!--ul class="gloss">
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
	</ul-->

</div><!-- /library -->
{/block}