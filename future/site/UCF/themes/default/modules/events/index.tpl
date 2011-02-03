{extends file="findExtends:common/base.tpl"}

{block name="body"}
<h2>Events</h2>
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
		<a class="arrow-back previous" href="?list=day&amp;day={urlencode($prev)}">Previous</a>
		<span class="date">{date('M j', $now)}</span>
		<a class="arrow next" href="?list=day&amp;day={urlencode($next)}">Next</a>
	</div>
	{/if}

{if count($events)}
{foreach $events as $event}
	<div class="block event{if $event->get_type() == 'ongoing'} ongoing{/if}">
		<header>
			<h3>{$event->get_title()}</h3>
			<div class="range">
				<span class="start">{date('M j', strtotime($event->get_startdate()))}</span>
				{if $event->get_enddate() and $event->get_type() == 'ongoing'}
				<span class="end">{date('M j', strtotime($event->get_enddate()))}</span>
				{/if}
			</div>
			{if $event->get_location_url() or $event->get_location_name()}
			<div class="location">
				{if $event->get_location_url() and $event->get_location_name()}
				{$event->get_location_name()}
				{else if $event->get_location_name()}
				{$event->get_location_name()}
				{/if}
			</div>
			{/if}
			<div class="times">
				{if date('i', strtotime($event->get_startdate())) == '00'}
				<span class="start">{date('g a', strtotime($event->get_startdate()))}</span>
				{else}
				<span class="start">{date('g:i a', strtotime($event->get_startdate()))}</span>
				{/if}
			</div>
		</header>
		<div class="description">
			{$event->get_description()}
		</div>
	</div>
{/foreach}
{else}
	<p class="block">No events found.</p>
{/if}
</div>
{/block}