(function($){

	$('document').ready(function(){

		$('#mdb-contest-fee select').each(function(){
			
			$(this).val( $(this).data('id') );

		});

	});

})(jQuery);