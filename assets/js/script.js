// In your Javascript (external .js resource or <script> tag)
$(function(){
	"use-strict";

	var $window = $(window),
			$body = $('body'),
    	$fullHeight = $(".full-height"); 
	if($body.find('#ResponsiveTable').length) {
		$('#ResponsiveTable').DataTable({
		responsive: true,
		language: {
			searchPlaceholder: 'Search...',
			sSearch: '',
			lengthMenu: '_MENU_ show',
		}
	});
	}

	$('#app-wrap select').select2({
		placeholder: "choose a value"
	});

    /* --------------------------------------------
      Make header height full screen 
    --------------------------------------------- */
		$fullHeight.height($window.height());
		console.log("wooow", $window.height());
    $window.resize(function () {
      $fullHeight.height($window.height());
    });

});