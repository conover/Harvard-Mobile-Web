{extends file="findExtends:common/base.tpl"}

{block name="body"}
<form action="" method="get">
	<input type="hidden" name="list" value="search" />
	<input type="search" placeholder="Search UCF Events" name="q" />
	<input type="submit" value="Search" />
</form>

<ul class="gloss tabs">
	<li class="first"><a href="?list=today">Today</a></li>
	<li><a href="?list=tomorrow">Tomorrow</a></li>
	<li><a href="?list=upcoming">Upcoming</a></li>
</ul>

<div id="text">
{if count($events)}
{foreach $events as $event}
	<div class="block">
		<h3>
			{$event->getTitle()}
			<span class="sub">Starts {date('F jS, Y', strtotime($event->getPubDate()))}</span>
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