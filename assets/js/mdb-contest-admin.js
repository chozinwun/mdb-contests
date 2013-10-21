(function($){

	$('document').ready(function(){

		$('#mdb-contest-fee select, #mdb-contestant select').each(function(){

			$(this).val( $(this).data('id') );

		});

	});

})(jQuery);