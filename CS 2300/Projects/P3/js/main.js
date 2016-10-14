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


	$(document).on("click", ".pic-button", function() {
		var form = $(this).parents('form:first');
		var title = $("input[name='title']",form).val();
		var imagelink = $("input[name='imagelink']",form).val();
		var caption = $("textarea[name='caption']",form).val();
		var pid = $("input[name='pid']",form).val();
		console.log(title);
		var data = {requestType: 'editImage', title: title, imagelink: imagelink, caption:caption, pid: pid};
		console.log(data);
		// Update
		editImage = $.ajax({
			url: 'includes/ajax.php',
			type: "POST",
			data: data,
			dataType: "text",
			error: function(error) {
				console.log(error);
			}
		});
		editImage.success(function(data) {
			console.log(data);
			$(".contentSubmit").text("");
			$(".contentSubmit").text("Photo Updated! Refresh the page to view the change.");
		})
	});

	$(document).on("click", ".alb-button", function() {
		var form = $(this).parents('form:first');
		var title = $("input[name='title']",form).val();
		var imagelink = $("input[name='imagelink']",form).val();
		var aid = $("input[name='aid']",form).val();
		console.log(title);
		var data = {requestType: 'editAlbum', title: title, imagelink: imagelink, aid: aid};
		console.log(data);
		// Update
		editAlbum = $.ajax({
			url: 'includes/ajax.php',
			type: "POST",
			data: data,
			dataType: "text",
			error: function(error) {
				console.log(error);
			}
		});
		editAlbum.success(function(data) {
			$(".contentSubmit").text("");
			$(".contentSubmit").text("Album Updated! Refresh the page to view the change.");
		})
	});
});