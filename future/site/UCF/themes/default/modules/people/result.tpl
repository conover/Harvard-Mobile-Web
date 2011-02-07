{if count($result)}
{$primary = $result[0]}
<article>
	<header>
		<h1>{$primary->name}</h1>
	</header>
	{foreach $result as $entry}
	<div class="entry">
		<section class="organization">
			{if !empty($entry->organization)}{$entry->organization}{/if}
			{if !empty($entry->department)}&#8226; {$entry->department}{/if}
		</section>
		<section class="building">
			{if !empty($entry->building)}{$entry->building}{/if}<!--
			-->{if !empty($entry->room)}, Room {$entry->room}{/if}
		</section>
		<section class="phone">
			<a href="tel:{$entry->phone}">{$entry->phone}</a>
		</section>
	</div>
	{/foreach}
	<footer>
		<div class="email"><a href="mailto:{$primary->email}">{$primary->email}</a></div>
		<div class="end"><!-- --></div>
	</footer>
</article>
{/if}