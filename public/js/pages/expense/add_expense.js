$(document).ready(function () {

    $.validator.addMethod("noDigits", function (value, element) {
        return this.optional(element) || value != value.match(/^[0-9]*/);
    }, "Only Numbers Not Allowed");

    
    $(".add-expense-form").validate({
        ignore: "input[type=hidden]",
        errorElement: "span",
        errorClass: "text-danger",
        debug: true,
        rules: {
            expense_name: {
                required: true,
            },
            amount: {
                required: true,
                number:true
            },
        },
        messages: {
            expense_name:{
                required: "Please enter expense name",
            },
            amount: {
                required: "Please enter amount",
            },
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
