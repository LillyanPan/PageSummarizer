$(document).ready(function() {
	$(".slider").hide();
	$(".item-border").mouseenter(function() {
		$(this).find(".slider").slideDown();
		$(this).find("img").addClass("image-dark");
	});
	$(".item-border").mouseout(function() {
		$(this).find(".slider").slideUp();
		$(this).find("img").removeClass("image-dark");
	});

	// $('.tab').click(function() {
	// 	$('#sidebar ul').removeClass('active');
	// 	$(this).parent().addClass('active');
	// });
	// $('#sidebar').click(function() {
	// 	// $('#sidebar ul').removeClass('active');
	// 	console.log("clicked");
	// 	$(this).parent().addClass('active');
	// });
	// $("a.home").live("addClass", 'active');
});