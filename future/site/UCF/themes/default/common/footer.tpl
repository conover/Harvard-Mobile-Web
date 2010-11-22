		<footer>
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
			{if $moduleID != 'home'}
			<div id="footerlinks">
				<a href="#top">Back to top</a> | <a href="../home/">{$SITE_NAME} home</a>{if $session_userID} | <a href="../login">{$session_userID} logged in</a>{/if}
			</div>
			{/if}
			
			{$footerHTML}
			
			{foreach $inlineJavascriptFooterBlocks as $script}
			<script type="text/javascript">
			{$script} 
			</script>
			{/foreach}
			{/block}
			{/strip}
		</footer>
		
		
		{if $platform=="computer"}
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
        <script src="/media/-/jquery.browser.min.js"></script>
        <script>
          jQuery(function(){
            $('#Body').addClass(jQuery.browser.name);
            $('#Body').addClass(jQuery.browser.name + '' + jQuery.browser.versionX);
          });
        </script>
    {/if}
		{block name="script"}{/block}
		
	</body>
</html>
