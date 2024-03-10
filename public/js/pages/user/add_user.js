$(document).ready(function () {

    $.validator.addMethod("noDigits", function (value, element) {
        return this.optional(element) || value != value.match(/^[0-9]*/);
    }, "Only Numbers Not Allowed");

    
    $(".add-user-form").validate({
        ignore: "input[type=hidden]",
        errorElement: "span",
        errorClass: "text-danger",
        debug: true,
        rules: {
            name: {
                required: true,
                minlength: 3,
                maxlength: 100,
                noDigits: true,
            },
            email: {
                required: true,
                email: true
            },
            phone: {
                required: true,
                number: true,
                minlength: 8,
                maxlength: 12,  
            },
            user_type: {
                required: true,
            },
            address: {
                required: true,
                maxlength:255
            },
            designation: {
                required: true,
            },
            shift_type: {
                required: true,
            },
            text_pass: {
                required: true,
            },
        },
        messages: {
            name: {
                required: "Please enter name",
            },
            email: {
                required: "Email is required",
            },
            phone: {
                required: "Mobile number is required",
            },
            user_type :{
                required: "Please select user type",
            },
            address: {
                required: "Address is required",
            },
            designation: {
                required: "Designation is required",
            },
            shift_type: {
                required: "Please select shift type",
            },
            text_pass: {
                required: "Password is required",
            }
        },
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
        submitHandler: function (form) {
            $(form).find('button[type="submit"]').attr("disabled", "disabled");
            form.submit();
        },
    });
});
