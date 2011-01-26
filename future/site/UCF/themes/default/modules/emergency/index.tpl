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
			<dt>University Police (main office)</dt><dd><a href="tel:4078235555">407-823-5555</a></dd>
			<dt>Safety Escort Patrol Services [SEPS]</dt><dd><a href="tel:4078232424">407-823-2424</a></dd>
			<dt>Victim Services</dt><dd><a href="tel:4078232425">407-823-2425</a></dd>
			<dt>Victim Services (after hours)</dt><dd><a href="tel:4078235555">407-823-5555</a></dd>
			<dt>Parking Services</dt><dd><a href="tel:4078235812">407-823-5812</a></dd>
		</dl>
		<div class="clear">&nbsp;</div>
	</div>
	
	<div class="block">
		<h3>Housing and Residence Life</h3>
		<dl>
			<dt>Main Office</dt><dd><a href="tel:4078234663">407-823-4663</a></dd>
			<dt>Maintenance</dt><dd><a href="tel:4078235587">407-823-5587</a></dd>
		</dl>
		<div class="clear">&nbsp;</div>
	</div>
	
	<div class="block">
		<h3>Environmental Health and Safety</h3>
		<p><a href="tel:4078236300">407-823-6300</a></p>
	</div>
	
</div>

{/block}