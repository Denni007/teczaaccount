$(document).ready(function () {

    $.validator.addMethod("noDigits", function (value, element) {
        return this.optional(element) || value != value.match(/^[0-9]*/);
    }, "Only Numbers Not Allowed");

    
    $(".add-payment-form").validate({
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
            payment_type: {
                required: true,    
            },
            bank_account: {
                required: function(element){
                    return $("#payment_type").val() == 2;
                }
            },
            payment_date :{
                required: true,
            },
            password :{
                required: true,
            },
        },
        messages: {
            // type:{
            //     required: "Please select payment type",
            // },
            // invoice_id: {
            //     required: "Please select Bill",
            // },
            // amount: {
            //     required: "Please enter amount",
            // },
            payment_type: {
                required: "Please select payment mode",
            },
            bank_account: {
                required: "Please select Bank Account",
            },
            payment_date: {
                required: "Please Enter payment date",
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
