$(".raw-material-form").validate({
    ignore: "input[type=hidden]",
    errorElement: "span",
    errorClass: "text-danger",
    debug: true,
    highlight: function (element, errorClass) {
        $(element).addClass("errorClass");
        $(element).addClass("error-border");
    },
    unhighlight: function (element, errorClass) {
        $(element).removeClass("errorClass");
        $(element).removeClass("error-border");
    },
    errorPlacement: function (error, element) {
        if (element.parents("div").hasClass("form-group")) {
            error.appendTo(element.parent());
        } else {
            error.insertAfter(element);
        }
    },
    rules: {
        raw_material_name: {
            required: true
        },
        qty: {
            required: true
        }
    },
    messages: {
        raw_material_name: {
            required :  "Please add raw material name"
        },
        qty: {
            required :  "Please add raw material quantity"
        }
    },

    submitHandler: function (form) {
        $(form).find('button[type="submit"]').attr("disabled", "disabled");
        form.submit();
    },
});
