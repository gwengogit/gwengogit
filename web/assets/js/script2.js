$(function(){
	//alert('toto');
	$('.product-list-background img').mouseover(function(e){
		e.stopPropagation();
		if(!$(this).prev('.hidden-product-price').is(":visible"))
		{
			$(this).prev('.hidden-product-price').show();
		}		
	});

	$('.product-list-background img').mouseout(function(e){
		e.stopPropagation();
		if($(this).prev('.hidden-product-price').is(":visible"))
		{
			$(this).prev('.hidden-product-price').hide();
		}
	});

	$('#product-more-detail').on('click', function(evenement){
		evenement.stopPropagation;
		if($('#product-page-more-div').hasClass('hidden'))
		{
			$("#product-page-more-div").show(500, 'swing').removeClass('hidden', 'swing').addClass('shown');
		}else{
			$("#product-page-more-div").hide(500, 'swing').removeClass('shown', 'swing').addClass('hidden');
		}
		return false;
	});

	$('#same-cat-products-title-div .action span').click(function(e){
		classValue = $(e.target).attr("class");

		if(classValue == 'next-product'){
			if($('.dark-layer.highlight').parent().next().attr('class') != undefined){
				$('.dark-layer.highlight').removeClass('highlight').parent().next().children('.dark-layer').addClass('highlight');
			}
			

		}else{
			if($('.dark-layer.highlight').parent().prev().attr('class') != undefined){
				$('.dark-layer.highlight').removeClass('highlight').parent().prev().children('.dark-layer').addClass('highlight');
			}
			
			
		}
	});
});