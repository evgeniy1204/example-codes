/*
 *  Document   : base_ui_activity.js
 *  Author     : pixelcave
 *  Description: Custom JS code used in Activity Page
 */

var BaseUIActivity = function() {
    // Randomize progress bars values
    var barsRandomize = function(){
        jQuery('.js-bar-randomize').on('click', function(){
            jQuery(this)
                .parents('.block')
                .find('.progress-bar')
                .each(function() {
                    var $this   = jQuery(this);
                    var $random = Math.floor((Math.random() * 91) + 10)  + '%';

                    $this.css('width', $random);

                    if ( ! $this.parent().hasClass('progress-mini')) {
                        $this.html($random);
                    }
                });
            });
    };

    // SweetAlert, for more examples you can check out https://github.com/t4t5/sweetalert
    var sweetAlert = function(){
        // Init a simple alert on button click
        jQuery('.js-swal-alert').on('click', function(){
            swal('Hi, this is a simple alert!');
        });

        // Init an success alert on button click
        jQuery('.js-swal-success').on('click', function(){
            swal('Success', 'Everything updated perfectly!', 'success');
        });

        // Init an error alert on button click
        jQuery('.js-swal-error').on('click', function(){
            swal('Oops...', 'Something went wrong!', 'error');
        });

        // Init an example confirm alert on button click
        jQuery('body').on('click', ".js-swal-confirm", function(){
            var allDelete = false;
            var action = 0;
            var thisBtn = $(this);
            if (! jQuery(this).hasClass("all-delete")) {
                var thisTr = jQuery(this).closest("tr");
                action = jQuery(this).data("action");
            } else {
                allDelete = true;
            }

            var btnText = 'Yes';
            var btnColor = action == 1 ? '#46c37b' : '#d26a5c';

            swal({
                title: 'Are you sure?',                
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: btnColor,
                confirmButtonText: btnText,
                html: false,
                preConfirm: function() {
                    return new Promise(function (resolve) {
                        setTimeout(function () {
                            resolve();
                        }, 50);
                    });
                }
            }).then(
                function (result) {
                    jQuery.ajax({
                        type: "POST",
                        data: {'status' : action, 'entities' : window.actionIdsManager.getIds(), 'extraEntityName' : thisBtn.data("extra-entity-name")},
                        success: function (data) {
                            if (thisBtn.data("extra-entity-name")) {
                                window.removeElementFromDom(thisBtn.closest("[data-extra]"));
                            } else {
                                if (allDelete) {
                                    actionIdsManager.getIds().forEach(function(item, i, arr) {
                                        var btn = $(".entity-id[data-entity='"+item+"']");
                                        btn.closest("tr").find("i.fa").removeClass("fa-times").addClass("fa-check");
                                        btn.closest("tr").find(".label-status span").removeClass("label-success").addClass("label-danger").text("Blocked");
                                        $(".on-select-disabled").attr("disabled", false);
                                    });
                                } else {
                                    if (thisTr.find(".label-status span").hasClass("label-success")) {
                                        thisTr.find(".label-status span").removeClass("label-success").addClass("label-danger").text("Blocked");
                                        thisTr.find("i.fa.fa-trash").removeClass("fa-trash").addClass("fa-check");
                                        thisTr.find(".js-swal-confirm").data("action", "1");
                                    } else {
                                        thisTr.find(".label-status span").removeClass("label-danger").addClass("label-success").text("Active");
                                        thisTr.find("i.fa.fa-check").removeClass("fa-check").addClass("fa-trash");
                                        thisTr.find(".js-swal-confirm").data("action", "0");
                                    }
                                }
                                window.actionIdsManager.clearIds();
                                $(".check-entity").prop("checked", false);
                            }
                            swal('Success!', 'Action has been success.', 'success');
                        },
                        error: function (data) {
                            swal('Error!', '', 'error');
                        }
                    })

                }, function(dismiss) {
                    window.actionIdsManager.clearIds();
                }
            );
        });

        // Init an example confirm alert on button click
        jQuery('body').on('click', ".js-swal-confirm-remove", function(){
            var thisBlock = $(this).closest(".top-del");
            var btn = $(this);

            swal({
                title: 'Are you sure?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: "#d26a5c",
                confirmButtonText: "Delete",
                html: false,
                preConfirm: function() {
                    return new Promise(function (resolve) {
                        setTimeout(function () {
                            resolve();
                        }, 50);
                    });
                }
            }).then(
                function (result) {
                    jQuery.ajax({
                        type: 'GET',
                        url: btn.data("url"),
                        beforeSend: function() {
                            setPreloader(btn.closest('.block-topic'));
                        },
                        success: function(data) {
                            if (data == 'ok') {
                                thisBlock.remove();
                            }
                            removePreloader($('.block-topic'));
                            swal('Success', 'Post has been deleted', 'success');
                        },
                        error: function() {
                            console.log("Error");
                            removePreloader($('.block-topic'));
                        }
                    })

                }, function(dismiss) {
                    window.actionUserManager.clearIds();
                }
            );
        });
    };

    return {
        init: function() {
            // Init randomize bar values
            barsRandomize();

            // Init SweetAlert
            sweetAlert();
        }
    };
}();

// Initialize when page loads
jQuery(function(){ BaseUIActivity.init(); });