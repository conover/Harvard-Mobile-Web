<!DOCTYPE html>
<html>
{strip}  
	<head>
		<meta charset="utf-8" />
		<title>{$moduleName}{if !$isModuleHome}: {$pageTitle}{/if}</title>
		<link href="{$minify['css']}" rel="stylesheet" media="all" type="text/css"/>
		
		{foreach $inlineCSSBlocks as $css}
		<style type="text/css" media="screen">
		{$css}
		</style>
		{/foreach}
		
		{block name="javascript"}
		{if strlen($GOOGLE_ANALYTICS_ID)}
		<script type="text/javascript">
			var _gaq = _gaq || [];
			_gaq.push(['_setAccount', '{$GOOGLE_ANALYTICS_ID}']);
			_gaq.push(['_trackPageview']);
		
			(function() {ldelim}
				var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
				ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
				var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
			{rdelim})();
		</script>
		{/if}
		
		{foreach $inlineJavascriptBlocks as $script}
		<script type="text/javascript">
		{$script}
		</script>
		{/foreach}
		
		{foreach $externalJavascriptURLs as $url}
		<script src="{$url}" type="text/javascript"></script>
		{/foreach}
		
		<script src="{$minify['js']}" type="text/javascript"></script>
		
		{if count($onOrientationChangeBlocks)}
		<script type="text/javascript">
			function onOrientationChange() {ldelim}
				{foreach $onOrientationChangeBlocks as $script}
				{$script}
				{/foreach}
			{rdelim}
			window.addEventListener("orientationchange", onOrientationChange, false);
			window.addEventListener("resize", onOrientationChange, false);
		</script>
		{/if}
		
		{if count($onLoadBlocks)}
		<script type="text/javascript">
			function onLoad() {ldelim}
				{foreach $onLoadBlocks as $script}
				{$script}
				{/foreach}
			{rdelim}
		</script>
		{/if}
		{/block}
		
		{block name="additionalHeadTags"}{/block}
	</head>
	
	<body>
		{block name="header"}
		<div id="Header">
			{if isset($topItem)}
			<div id="new"><a href="/about/new.php"><span class="newlabel">NEW:</span>{$topItem}</a></div>
			{/if}
			<h1><a href="/home/">UCF<span>Mobile</span></a></h1>
		</div>
		{/block}