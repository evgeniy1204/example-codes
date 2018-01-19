initializeSearchFunctions();

function initializeSearchFunctions() {
	eventListenerSelectCourse();
	eventListenerDynamicSearch();
	eventSortBy();
	eventShowsPer();
}

function eventListenerSelectCourse() {
	$("#course-search").on("change", function() {
		$.ajax({
			type: 'POST',
			url: $(this).closest("form").attr("action"),
			data: {'course' : $(this).val()},
			success: function(html) {
				$('#class-search').replaceWith(
		            $(html).find('#class-search')
		        );
		        $('#year-search').replaceWith(
		            $(html).find('#year-search')
		        );
			},
			error: function(data) {
				console.log("error");
			},
			beforeSend: function() {

			}
		})
	});
}

function eventListenerDynamicSearch() {
	$("input#dynamic-search").on("change keyup keypress", $.debounce(300, function(e) {
		if (e.keyCode == 13) {
            e.preventDefault();
		    return false;
		}
        if ($(this).val().length >= 2) {
            sendAjaxSearch({data : $(this).val()}, $(this).closest("form").attr("action"));
        } else {
            sendAjaxSearch({data : ""}, $(this).closest("form").data("url-reset"));
        }
	}));
}

/**
* When sorting synopsis per selected
*/
function eventSortBy() {
	$("body").on("change", "#sortBy", function() {
		var sortBy = $("#sortBy option:selected").val();
		if ($("input[name=dynamic-search]").val()) {
			sendAjaxSearch({data : $("input[name=dynamic-search]").val(), 'sortByField' : $("#sortBy option:selected").val()}, $("input[name=dynamic-search]").closest("form").attr("action"));
		} else {
			document.location.href = replaceUrlParam(document.location.href, 'sortBy', sortBy);
		}
	})
}

/**
* How much synopsis show in page
*/
function eventShowsPer() {
	$("body").on("change", "#showPer", function() {
		var showPer = $("#showPer option:selected").val();
		document.location.href = replaceUrlParam(document.location.href, 'show', showPer);
	})
}

function sendAjaxSearch(data, url) {
	$.ajax({
        type: 'POST',
        data: data,
        url: url,
        beforeSend: function() {
        	$(".block").addClass("block-opt-refresh");
        },
        success: function(data) {
            $("#content").html(data);
            $(".block").removeClass("block-opt-refresh");
        },
        error: function(data) {
            console.log("error");
            $(".block").removeClass("block-opt-refresh");
        },
    })
}