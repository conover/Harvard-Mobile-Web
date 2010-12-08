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
	<div class="block event{if $event->get_type() == 'ongoing'} ongoing{/if}">
		<header>
			<h3>{$event->get_title()}</h3>
			<div class="starttime">Starts {date('F jS, Y', strtotime($event->get_startdate()))}</div>
			<div class="starttime">Ends {date('F jS, Y', strtotime($event->get_enddate()))}</div>
			{if $event->get_location_url() or $event->get_location_name()}
			<div class="location">
				{if $event->get_location_url() and $event->get_location_name()}
				<a href="{$event->get_location_url()}">{$event->get_location_name()}</a>
				{else if $event->get_location_name()}
				{$event->get_location_name()}
				{/if}
			</div>
			{/if}
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