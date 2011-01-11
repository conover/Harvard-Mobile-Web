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
				<a href="/customize/">
					<span class="heading">Customize</span>
					<span class="description">Change options</span>
				</a>
				<a href="http://www.ucf.edu">
					<span class="heading">UCF.edu</span>
					<span class="description">Visit full site</span>
				</a>
				<a href="mailto:webcom@mail.ucf.edu?subject=UCF Mobile Website Feedback">
					<span class="heading">Feedback</span>
					<span class="description">Suggestions &amp; ideas</span>
				</a>
				<a href="mailto:webcom@mail.ucf.edu?subject=UCF Mobile Website Feedback">
					<span class="heading">Contact UCF</span>
					<span class="description">Contact Information</span>
				</a>
			</div>
			
			<div id="university-information">
				<span class="name">University of Central Florida</span>
				<span class="street">4000 Central Florida Blvd</span>
				<span class="city">Orlando, Florida 32826</span>
				<span class="about"><a href="#">About this site</a></span>
			</div>
			
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
