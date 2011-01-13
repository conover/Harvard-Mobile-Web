{extends file="findExtends:common/base.tpl"}

{block name="body"}
<div id="Twitter">
	<p>Latest tweets about UCF</p>
	<ul>
		{foreach $tweets as $tweet}
		<li>
			<img src="{$tweet->user->profile_image_url}" class="user">
			<div class="tweet">
				<a href="http://twitter.com/{$tweet->user->screen_name}" class="user">{$tweet->user->screen_name}</a>
				{linkify($tweet->text)}
				
				{textual_difference(strtotime($tweet->created_at))}
			</div>
		</li>
		{/foreach}
	</ul>
</div>
{/block}
