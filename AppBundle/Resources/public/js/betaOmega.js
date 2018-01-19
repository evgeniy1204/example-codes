
initializeApp();

function initializeApp() {
    $("#is-agree").on("click", function() {
        $(".is-approve").attr("disabled", !$("#is-agree").is(":checked"));
    })

    $("#modal-terms .agree").on("click", function() {
        $("#is-agree").click();
    })

    $("body").on("click", ".complaint[data-entity]", function() {
        var entity = $(this).data("entity");
        $("#modal-send-report .entity-complaint").val(entity);
    });

    sendComplaintForm();
    getComplaintForm();

    //Substitution fields when change course
    var $course = $('#course_synopsis');
    // When sport gets selected ...
    $course.change(function() {
        // ... retrieve the corresponding form.
        var $form = $(this).closest('form');
        // Simulate form data, but only include the selected sport value.
        var data = {};
        data[$course.attr('name')] = $course.val();
        // Submit data via AJAX to the form's action path.
        $.ajax({
            url : $form.attr('action'),
            type: $form.attr('method'),
            data : data,
            success: function(html) {
                // Replace current classes field ...
                $('#course_class').replaceWith(
                    $(html).find('#course_class')
                );
                // Replace current year field ...
                $('#course_year').replaceWith(
                    $(html).find('#course_year')
                );
            }
        });
    });

    $(window).keydown(function(event){
        if(event.keyCode == 13) {
            event.preventDefault();
            return false;
        }
    });

    $(".contact-admin").on("click", function () {
        window.actionUserManager.addEmail($(this).data("admin-email"));
    })

    sendEmail();

}


/**
* Set preloader in element
* @param el
*/
function setPreloader(el) {
    $(el).addClass("block block-opt-refresh");
}

/**
* Remove preloader in element
* @param el
*/
function removePreloader(el) {
    $(el).removeClass("block block-opt-refresh");
}

/**
* Scroll to element
* @param el
*/
function scrollTo(el) {
    $('html, body').animate({ scrollTop: $(el).offset().top }, 'slow');
    return false;
}

function replaceUrlParam(url, paramName, paramValue){
    if(paramValue == null)
        paramValue = '';
    var pattern = new RegExp('\\b('+paramName+'=).*?(&|$)')
    if(url.search(pattern)>=0){
        return url.replace(pattern,'$1' + paramValue + '$2');
    }
    return url + (url.indexOf('?')>0 ? '&' : '?') + paramName + '=' + paramValue 
}



/**
 * Remove element from DOM when deleted
 * @param el
 */
function removeElementFromDom(el) {
    if (el) {
        $(el).remove();
    }
}

function sendEmail() {
    $(".notificate-email").on("submit", function(e){
        e.preventDefault();
        var btn = $(this).find("button");
        var form = $(this);
        btn.attr("disabled", true);
        $.ajax({
            type: "POST",
            url: form.attr("action"),
            data: {'subject' : $(this).find("[name=subject]").val(), 'text' : $(this).find("[name=text]").val(), 'emails' : window.actionUserManager.getEmails()},
            beforeSend: function () {
                setPreloader($(".block"))
            },
            success: function (data) {
                removePreloader($(".block"));
                $("#modal-send-msg").modal('hide');
                swal("Success", "Email sent", "success");
                btn.attr("disabled", false);
                $(".check-entity").prop("checked", false);
                form.find("input, textarea").val("");
                window.actionUserManager.clearEmails();
                window.actionIdsManager.clearIds();
            },
            error: function (data) {
                removePreloader($(".block"));
                $("#modal-send-msg").modal('hide');
                swal("Error", "Error", "error");
                btn.attr("disabled", false);
            }
        })
    });
}

function sendComplaintForm() {
    $("body").on("click", ".send-complaint-form", function () {
        $.ajax({
            type: "POST",
            url: $(this).closest("form").attr("action"),
            data: $(this).closest("form").serialize(),
            beforeSend: function () {
                setPreloader($('.modal .block'));
            },
            success: function (data) {
                $("#modal-send-report").modal('hide').remove();
            },
            error: function (data) {
                $("#modal-send-report").modal('hide').remove();
            }
        })
    })
}

function getComplaintForm() {
    $("body").on("click", ".complaint", function (e) {
        e.preventDefault();
        $.ajax({
            type: "GET",
            url: $(this).data("url"),
            beforeSend: function () {
                setPreloader($(".object-tabs-content"));
            },
            success: function (data) {
                $("#complaint-form").html(data);
                $("#modal-send-report").modal('show');
                removePreloader($(".object-tabs-content"));
            },
            error: function (data) {
                removePreloader($(".object-tabs-content"));
            }
        })
    })
}

$('#modal-send-report').on('hidden.bs.modal', function () {
    $(this).remove();
})