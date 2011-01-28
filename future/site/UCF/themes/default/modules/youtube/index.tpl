{extends file="findExtends:common/base.tpl"}

{block name="body"}

	<div class="text">
		<div id="youtube">
			{if $videos}
				{foreach $videos as $video}
					{if $video['error']}
						<!-- error parsing video -->
					{else}
						<div class="block">
							<h3>{$video['title']}</h3>
							{if $platform !="computer"}
							<object height="175" width="290">
								<param name="movie" value="http://www.youtube.com/v/{$video['id']}&amp;hl=en&amp;fs=1&amp;rel=0&amp;hd=1" />
								<param name="allowFullScreen" value="true" />
								<param name="allowscriptaccess" value="always" />
								<embed height="175" width="290" src="http://www.youtube.com/v/{$video['id']}&amp;hl=en&amp;fs=1&amp;rel=0&amp;hd=1" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true"></embed>
							</object>
							{elseif false}
							<iframe class="youtube-player" type="text/html" width="290" height="175" src="http://www.youtube.com/embed/{$video['id']}" frameborder="0"></iframe>
							{else}
							<div class="icon">
								<a href="{$video['link']}">{$video['icon']}</a>
							</div>
							{/if}
							<div class="desc">{$video['desc']}</div>
							<div class="foot">{$video['foot']}</div>
							<div class="clear">&nbsp;</div>
						</div>
					{/if}
				{/foreach}
			{else}
				<div class="block">
					<p>Error with youtube feed.</p>
					<p>Visit youtube directly at: <a href="http://m.youtube.com/ucf">http://m.youtube.com/ucf</a></p>
				</div>
			{/if}
		</div>
	</div>
	
	<ul class="gloss">
		<li class="arrow"><a href="http://m.youtube.com/ucf">More on YouTube</a></li>
	</ul>

{/block}