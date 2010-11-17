<article class="directory">
	<header>
		<h1>{$result.name}</h1>
		<div class="phone"><a href="tel:{$result.phone}">{$result.phone}</a></div>
		<div class="email"><a href="mailto:{$result.email}">{$result.email}</a></div>
	</header>
	<section class="organization">
		{if !empty($result.organization_name)}{$result.organization_name}{/if}
		{if !empty($result.department_name)}{$result.department_name}{/if}
	</section>
	<section class="building">
		{if !empty($result.building_name)}{$result.building_name}{/if}
		{if !empty($result.room)}{$result.room}{/if}
	</section>
</article>
