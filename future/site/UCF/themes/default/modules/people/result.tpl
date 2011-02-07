{if count($result)}
{$primary = $result[0]}
<article>
	<header>
		<h1>{$primary->name}</h1>
		<div class="email"><a href="mailto:{$primary->email}">{$primary->email}</a></div>
	</header>
	{$first = True}
	{foreach $result as $entry}
	<div class="entry{if $first} first{$first = False}{/if}">
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
</article>
{/if}