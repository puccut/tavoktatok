jQuery(document).ready(function($) {
	$(".nsu-form p").mouseover(function(){
		$obj = $(".nsu-error",this);
		$obj.fadeOut();
	});
});