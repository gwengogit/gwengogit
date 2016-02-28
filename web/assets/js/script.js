$(function(){

	$(window).resize(function(){
		$('.gallery-item').each(function(){
			$(this).height($(this).width());
			$('#header img').width($('#header').width()).height('100%');
		});
	});

	$('#header').cycle({
		fx: 'scrollLeft',
		timeout: 32000,
		speed: 1500,
		prev: $('#home-slider-left'),
		next: $('#home-slider-right'),
		callback: function(){
			alert('toto');
		}
	});

	$('.gallery-item').each(function(){
		$(this).height($(this).width());
	});

	$('.gallery-item-content').mouseover(function(e){
		e.stopPropagation();
		$(this).children().show();
	});

	$('.gallery-item-content').mouseout(function(e){
		e.stopPropagation();
		$(this).children().hide();
		//$(this).children().hide();
	});

	function MapInit(){
		var mapCanvas = document.getElementById('home-map');
		var mapOptions = {
          center: new google.maps.LatLng(48.843122, 2.402050),
          zoom: 15,
          mapTypeId: google.maps.MapTypeId.ROADMAP
        }

    	var map = new google.maps.Map(mapCanvas, mapOptions);
	}

	google.maps.event.addDomListener(window, 'load', MapInit);



});
