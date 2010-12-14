{extends file="findExtends:common/base.tpl"}

{block name="body"}
	
<div class="text">
	{if $emergency}
	<div class="alert">
		<h2>Alert</h2>
		<div class="block">
			{$emergency->get_content()}
		</div>
	</div>
	{/if}
	
	<h2>Emergency Contact Numbers</h2>
	<div class="block">
		<h3>University Police</h3>
		<p><a id="call911" href="tel:911">Emergency Call 911</a></p>
		<dl>
			<dt>Safety Escort Patrol Services [SEPS]</dt><dd>407-823-2424</dd>
			<dt>Victim Services</dt><dd>407-823-2425</dd>
			<dt>Victim Services (after hours)</dt><dd>407-823-5555</dd>
			<dt>Parking Services</dt><dd>407-823-5812</dd>
			<dt>Health Services Information</dt><dd>407-823-2701</dd>
		</dl>
		<div class="clear">&nbsp;</div>
	</div>
	
	<div class="block">
		<h3>University Housing</h3>
		<dl>
			<dt>Main Office</dt><dd>407-823-4663</dd>
			<dt>Maintenance</dt><dd>407-823-5587</dd>
		</dl>
		<div class="clear">&nbsp;</div>
	</div>
	
	<div class="block">
		<h3>Dept of Environmental Health &amp; Safety</h3>
		<p>407-823-6300</p>
	</div>
	
</div>
	
{/block}