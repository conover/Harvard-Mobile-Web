<div id="share">
	<a onclick="showShare()"><img src="/device/compliant/common/images/share.png"/></a>
	<div id="sharesheet">
		<div id="shareback"> </div>
		<div id="sharedialog">
			<h1>Share this story</h1>
			<ul>
				<li><a href="mailto:?"><img src="/device/compliant/common/images/button-email.png" alt="" width="32" height="32" />Email</a></li>
				<li>
				    <a href="http://m.facebook.com/sharer.php?u={$urlToBeShared}&t={$shareRemark}">
				        <img src="/device/compliant/common/images/button-facebook.png" 
			            alt="" width="32" height="32" />Facebook
			        </a>
				</li>
				<li>
				    <a href="http://m.twitter.com/share?url={$urlToBeShared}&amp;text={$shareRemark}&amp;Via=Harvard">
				        <img src="/device/compliant/common/images/button-twitter.png" alt="" width="32" height="32" />Twitter
				    </a>
                </li>
			</ul>
			<a onclick="hideShare()">Cancel</a>
		</div>
	</div>
</div>
