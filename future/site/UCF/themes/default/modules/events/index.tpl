{extends file="findExtends:common/base.tpl"}

{block name="body"}
<ul class="gloss tabs">
	<li class="first"><a href="?list=today">Today</a></li>
	<li><a href="?list=tomorrow">Tomorrow</a></li>
	<li><a href="?list=upcoming">Upcoming</a></li>
</ul>

<div id="text">
{foreach $events as $event}
	<div class="block">
		<h3>
			{$event->getTitle()}
			<span class="sub">Starts {date('l, F jS', strtotime($event->getPubDate()))}</span>
		</h3>
		<div class="description">
		{$event->getDescription()}
		</div>
		<a href="{$event->getLink()}">Details</a>
	</div>
{/foreach}
</div>

{/block}