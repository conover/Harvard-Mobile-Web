{extends file="findExtends:common/base.tpl"}

{block name="body"}
<div id="Twitter">
	<ul>
		{foreach $tweets as $tweet}
		<li>
			<img src="{$tweet->user->profile_image_url}" class="user">
			{var_dump($tweet)}
			<div class="tweet">
				<a class="user">{$tweet->user->screen_name}</a>
				{linkify($tweet->text)}
			</div>
		</li>
		{/foreach}
	</ul>
</div>
{/block}
