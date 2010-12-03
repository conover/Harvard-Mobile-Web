<article>
	<header>
		<h1>{$result->name}</h1>
	</header>
	<section class="organization">
		{if !empty($result->organization)}{$result->organization}{/if}
		{if !empty($result->department)}&#8226; {$result->department}{/if}
	</section>
	<section class="building">
		{if !empty($result->building)}{$result->building}{/if}<!--
		-->{if !empty($result->room)}, Room {$result->room}{/if}
	</section>
	<footer>
		<div class="phone"><a href="tel:{$result->phone}">{$result->phone}</a></div>
		<div class="email"><a href="mailto:{$result->email}">{$result->email}</a></div>
		<div class="end"><!-- --></div>
	</footer>
</article>
