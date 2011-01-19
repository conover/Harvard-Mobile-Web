(function ($){
	$.fn.Gallery = function(){
		var _this    = $(this);
		var slider   = $("<ul id='gallery-slider'>");
		var prefetch = $("<div id='prefetch'>");
		_this.after(slider);
		_this.after(prefetch);
		
		//Pre-fetch images
		_this.children('li').each(function(){
			var img = $('<img>');
			img.attr('src', $(this).attr('data-original').replace('_m.jpg', '_z.jpg'));
			prefetch.append(img);
		});
		
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
			slider.append(active.clone());
			slider.append(prev.clone());
			slider.append(next.clone());
			
			slider.children('.active').each(function(){
				$(this).children('img').attr('src', 
					$(this).attr('data-original').replace('_m.jpg', '_z.jpg')
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
		activate(_this.children().first());
		prefetch.hide();
	};
	$('#images').Gallery();
})(jQuery);