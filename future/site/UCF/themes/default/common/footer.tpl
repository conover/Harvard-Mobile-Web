		<div id="Footer">
			{strip}
			{if $moduleDebug && count($moduleDebugStrings)}
			<p class="legend nonfocal">
			{foreach $moduleDebugStrings as $string}
				<br/>{$string}
			{/foreach}
			</p>  
			{/if}

			{capture name="footerHTML" assign="footerHTML"}	
			{if $showDeviceDetection}
			<table class="devicedetection">
				<tr><th colspan="2">User Agent:</th></tr>
				<tr><td colspan="2">{$smarty.server.HTTP_USER_AGENT}</td></tr>
				<tr><th>Pagetype-Platform:</th><td>{$pagetype}-{$platform}</td></tr>
				<tr><th>Certificates:</th><td>{if $supportsCerts}yes{else}no{/if}</td></tr>
			</table>
			{/if}
			{/capture}
			
			{block name="footer"}
			
			{$footerHTML}
			
			<ul class="gloss" id="FooterNav">
				{if $moduleName == 'Home'}
				<li id="Customize" class="arrow"><a href="/customize/">Customize</a></li>
				{/if}
				<li id="Bookmark"><a href="javascript:bookmark()">Bookmark</a></li>
				<li id="Main"><a href="http://www.ucf.edu">UCF.edu</a></li>
				<li id="Copyright">&copy; 2010 University of Central Florida</li>
			</ul>
			
			{foreach $inlineJavascriptFooterBlocks as $script}
			<script type="text/javascript">
			{$script} 
			</script>
			{/foreach}
			{/block}
			{/strip}
		</div>
		
		
		{if $platform=="computer"}
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
		<script src="/media/-/jquery.browser.min.js"></script>
		<script>
			jQuery(function(){
				$('#body').addClass(jQuery.browser.name);
				$('#body').addClass(jQuery.browser.name + '' + jQuery.browser.versionX);
			});
			</script>
		{/if}
		{block name="script"}{/block}
		
	</body>
</html>
