{extends file="findExtends:common/base.tpl"}

{block name="body"}
<div id="Twitter" class="text">
	<h2>UCF on Twitter</h2>
	{if !$error}
	<ul>
		{foreach $tweets as $tweet}
		<li class="block">
			<img src="{$tweet->user->profile_image_url}" class="user">
			<div class="tweet">
				<a href="http://twitter.com/{$tweet->user->screen_name}" class="user">{$tweet->user->screen_name}</a>
				{linkify($tweet->text)}
				<span class="when">{textual_difference(strtotime($tweet->created_at))}</span>
			</div>
		</li>
		{/foreach}
	</ul>
	{else}
	<p>Oops, tweets for UCF are unavailable at this time.</p>
	{/if}
</div>
{/block}
