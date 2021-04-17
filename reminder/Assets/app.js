$(function () {

    const pleaseWait = () => {

        swal("PROCESSING", "Please Wait","/assets/images/ajax-loader.gif", {
            buttons: {},
            closeOnClickOutside:false,
            closeOnEsc:false,
            showLoaderOnConfirm:true
        })

    };


    $(".reminderSetup").submit(function () {

        swal("TRANSACTION CONFIRM", "Do you confirm the information?","info", {
            buttons: {
                cancel: {
                    text: "No",
                    value: false,
                    visible: true
                },
                yes: {
                    text: "Yes",
                    value: true
                }
            }
        }).then((x)=>{
            if(x){


                $.ajax({
                    type: "POST",
                    url: "/app/plugin/reminder/setup",
                    data: $(this).serialize(),
                    dataType: "json",
                    success: function (response) {

                        if(response.status === "success"){

                            location.reload();

                        }else{
                            swal(response.title,response.message,response.status, {
                                buttons: {
                                    cancel: {
                                        text: "Try Again",
                                        value: false,
                                        visible: true
                                    }
                                }
                            });
                        }

                    }
                });


            }
        });

    });


    $(".reminderCreate").submit(function () {

        swal("TRANSACTION CONFIRM", "Do you confirm the information?","info", {
            buttons: {
                cancel: {
                    text: "No",
                    value: false,
                    visible: true
                },
                yes: {
                    text: "Yes",
                    value: true
                }
            }
        }).then((x)=>{
            if(x){


                $.ajax({
                    type: "POST",
                    url: "/app/plugin/reminder/create",
                    data: $(this).serialize(),
                    dataType: "json",
                    success: function (response) {

                        if(response.status === "success"){

                            location.reload();

                        }else{
                            swal(response.title,response.message,response.status, {
                                buttons: {
                                    cancel: {
                                        text: "Try Again",
                                        value: false,
                                        visible: true
                                    }
                                }
                            });
                        }

                    }
                });


            }
        });

    });


    $.fn.dataTable.ext.buttons.reload = {
        text: 'Data Refresh',
        action: function ( e, dt, node, config ) {
            dt.ajax.reload();
        }
    };


    window.reminderTableEx = $("#reminderTableEx-table").DataTable({
        responsive: true,
        dom: 'Bfrtip',
        ajax: {
            url: "/app/plugin/reminder/list",
            type: "POST",
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]]
        },
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
        buttons: ["colvis","copy", "excel", "pdf", "reload"],
        lengthChange: !1,
        columnDefs: [ {
            targets: -1,
            data: null,
            defaultContent: "<button class='reminderDelete btn btn-primary btn-sm waves-effect waves-light'>Remove</button>"
        },
            {
                render: function (data, type, full, meta) {
                    return "<div class='text-wrap width-200'>" + data + "</div>";
                },
                targets: 1
            }
        ]
    });

    window.reminderTableEx.buttons().container().appendTo("#datatable-buttons_wrapper .col-md-6:eq(0)");

    $('#reminderTableEx-table tbody').on( 'click', 'button.reminderDelete', function () {

        let getCode = $($(this).parents('tr').children()[0]).html();

        swal("OPERATION CONFIRM", "Do you confirm the deletion?","info", {
            buttons: {
                cancel: {
                    text: "No",
                    value: false,
                    visible: true
                },
                yes: {
                    text: "Yes",
                    value: true
                }
            }
        }).then((x)=>{
            if(x){

                $.ajax({
                    type: "POST",
                    url: "/app/plugin/reminder/remove",
                    data: {uuid:getCode},
                    dataType: "json",
                    success: function (response) {

                        if(response.status === "success"){

                            window.reminderTableEx.ajax.reload();

                            swal.close();

                        }else{
                            swal(response.title,response.message,response.status, {
                                buttons: {
                                    cancel: {
                                        text: "Try Again",
                                        value: false,
                                        visible: true
                                    }
                                }
                            });
                        }

                    }
                });


            }
        });


    });



});