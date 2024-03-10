$(document).ready(function () {

    $.validator.addMethod("noDigits", function (value, element) {
        return this.optional(element) || value != value.match(/^[0-9]*/);
    }, "Only Numbers Not Allowed");

    
    $(".add-receipt-form").validate({
        ignore: "input[type=hidden]",
        errorElement: "span",
        errorClass: "text-danger",
        debug: true,
        rules: {
            // type: {
            //     required: true,
            // },
            // invoice_id: {
            //     required: true,
            // },
            // amount: {
            //     required: true,
            //     number:true
            // },
            receipt_type: {
                required: true,    
            },
            bank_account: {
                required: function(element){
                    return $("#receipt_type").val() == 2;
                }
            },
            receipt_date :{
                required: true,
            },
            password :{
                required: true,
            },
        },
        messages: {
            // type:{
            //     required: "Please select receipt type",
            // },
            // invoice_id: {
            //     required: "Please select Bill",
            // },
            // amount: {
            //     required: "Please enter amount",
            // },
            receipt_type: {
                required: "Please select receipt mode",
            },
            bank_account: {
                required: "Please select Bank Account",
            },
            receipt_date: {
                required: "Please Enter receipt date",
            },
            password: {
                required: "Please enter password",
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
