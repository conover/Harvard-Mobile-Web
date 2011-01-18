{extends file="findExtends:common/base.tpl"}


{block name="body"}
<div id="Flickr">
	<h2>UCF on Flickr</h2>
	<ul class="gloss tabs">
		<li><a class="grid" href="#">Grid</a></li>
		<li><a class="full" href="#">Fullscreen</a></li>
		<li><a href="#">Flickr</a></li>
	</ul>
	<ul id="images">
		{foreach $items as $id=>$item}
		{$first    = $id == 0}
		{$image    = $item->get_enclosure()}
		{$original = $image->link}
		{if $image->thumbnails}
			{$thumbnail = $image->thumbnails[0]}
			{$src       = $thumbnail}
		{else}
			{$thumbnail = ''}
			{$src       = $original}
		{/if}
		<li {if $first}class="active"{/if} data-thumbnail="{$thumbnail}" data-original="{$original}" data-id="{$id}">
			<img src="{$src}" alt="{$item->get_title()}" >
		</li>
		{/foreach}
	</ul>
</div>
{/block}

{block name="script"}
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
<script type="text/javascript" charset="utf-8">
	(function ($){
		$.fn.Gallery = function(){
			var _this      = $(this);
			
			var slider = $("<ul id='gallery-slider'>");
			_this.after(slider);
			
			var activate = function(active){
				//Change originals back to thumbnail and remove classes
				_this.children('.next, .previous').removeClass('previous next');
				_this.children('.active').removeClass('active');
				
				active.addClass('active');
				
				var prev = active.prev();
				var next = active.next();
				
				if (prev.length < 1){
					prev = _this.children().last();
				}
				if (next.length < 1){
					next = _this.children().first();
				}
				prev.addClass('previous');
				next.addClass('next');
				
				slider.empty();
				slider.append(prev.clone());
				slider.append(active.clone());
				slider.append(next.clone());
				
				slider.children('.active').each(function(){
					$(this).children('img').attr('src', 
						$(this).attr('data-original').replace('_m.jpg', '_b.jpg')
					);
				});
				
				slider.children('li').click(function(){
					var item = _this.children('[data-id="'+ $(this).attr('data-id') +'"]');
					activate(item);
				});
			};
			
			$('a.grid').click(function(){
				slider.hide();
				_this.show();
			});
			$('a.full').click(function(){
				slider.show();
				_this.hide();
			});
			
			_this.children('li').click(function(){
				activate($(this));
				$('a.full').click();
			});
			
			$('a.grid').click();
		};
		$('#images').Gallery();
	})(jQuery);
</script>
{/block}
