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
			
			<div id="footer-links">
				<a class="customize" href="/customize/">
					<span class="heading">Customize</span>
					<span class="description">Change options</span>
				</a>
				<a class="feedback" href="mailto:webcom@mail.ucf.edu?subject=UCF Mobile Website Feedback">
					<span class="heading">Feedback</span>
					<span class="description">Suggestions &amp; ideas</span>
				</a>
			</div>
			
			<div id="university-information">
				<div class="contact">
					<span class="name">University of Central Florida</span>
					<span class="street">4000 Central Florida Blvd</span>
					<span class="city">Orlando, Florida 32826</span>
					<span class="phone">(407) 823-2000</span>
				</div>
				<div class="links">
					<span class="about"><a href="/about/">About this site</a></span>
					<span class="main-site"><a href="http://www.ucf.edu">UCF.edu</a></span>
				</div>
			</div>
			{/block}
			{/strip}
		</div>
		
		
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
		<script src="/media/-/jquery.browser.min.js"></script>
		{foreach $externalJavascriptURLs as $url}
		<script src="{$url}" type="text/javascript"></script>
		{/foreach}
		<script src="{$minify['js']}" type="text/javascript"></script>
		<script>
			jQuery(function(){
				$('#body').addClass(jQuery.browser.name);
				$('#body').addClass(jQuery.browser.name + '' + jQuery.browser.versionX);
			});
		</script>
		{block name="script"}{/block}
		{foreach $inlineJavascriptFooterBlocks as $script}
		<script type="text/javascript">
		{$script} 
		</script>
		{/foreach}
		
		{if strlen($CHARTBEAT_ID) and strlen($CHARTBEAT_DOMAIN)}
		<script type="text/javascript">
		var _sf_async_config    = {};
		_sf_async_config.uid    = {$CHARTBEAT_ID};
		_sf_async_config.domain = '{$CHARTBEAT_DOMAIN}';
		(function(){
			function loadChartbeat() {
				window._sf_endpt=(new Date()).getTime();
				var e = document.createElement('script');
				e.setAttribute('language', 'javascript');
				e.setAttribute('type', 'text/javascript');
				e.setAttribute('src', (
					("https:" == document.location.protocol) ? 
					"https://a248.e.akamai.net/chartbeat.download.akamai.com/102508/" :
					"http://static.chartbeat.com/") +
				"js/chartbeat.js");
				document.body.appendChild(e);
			}
			var oldonload = window.onload;
			window.onload = (typeof window.onload != 'function') ? loadChartbeat : function() {
				oldonload(); loadChartbeat();
			};
		})();
		</script>
		{/if}
	</body>
</html>
