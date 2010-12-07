{extends file="findExtends:common/base.tpl"}

{block name="body"}
<ul class="gloss">
	<li class="search">
		<form method="get">
			<input type="hidden" name="list" value="search" />
			<div><input type="search" placeholder="Search UCF Events" name="q" {if $search_q}value="{$search_q}"{/if}></div>
			<input type="submit" value="Search" />
		</form>
	</li>
</ul>

<ul class="gloss tabs">
	<li class="first"><a href="?list=today">Today</a></li>
	<li><a href="?list=tomorrow">Tomorrow</a></li>
	<li><a href="?list=upcoming">Upcoming</a></li>
</ul>

<div class="text">
	{if $next and $prev}
	<div class="group">
		<a class="arrow-back previous" href="?list=day&amp;day={urlencode($prev)}">{date('M jS, Y', $prev)}</a>
		<a class="arrow next" href="?list=day&amp;day={urlencode($next)}">{date('M jS, Y', $next)}</a>
	</div>
	{/if}

{if count($events)}
{foreach $events as $event}
	<div class="block">
		<h3>
			{$event->getTitle()}
			<span>Starts {date('F jS, Y', strtotime($event->getPubDate()))}</span>
		</h3>
		<div class="description">
		{$event->getDescription()}
		</div>
		<a href="{$event->getLink()}">Details</a>
	</div>
{/foreach}
{else}
	<p class="block">No events found.</p>
{/if}
</div>

{/block}