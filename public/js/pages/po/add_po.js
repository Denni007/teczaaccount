$(document).ready(function () {

    $.validator.addMethod("noDigits", function (value, element) {
        return this.optional(element) || value != value.match(/^[0-9]*/);
    }, "Only Numbers Not Allowed");

    
    $(".add-pi-form").validate({
        ignore: "input[type=hidden]",
        errorElement: "span",
        errorClass: "text-danger",
        debug: true,
        rules: {
            bill_no: {
                required: true,
            },
            vendor: {
                required: true,
            },
            bill_type: {
                required: true,    
            },
            invoice_type: {
                required: true,    
            },
            // bank_account: {
            //     required: function(element){
            //         return $("#bill_type").val() == 2;
            //     }
            // },
            amount :{
                required: true,
                number:true
            },
            gst: {
                required: function(element){
                    return $("#bill_type").val() == 2;
                },
                number:true
            },
            // cgst: {
            //     required: function(element){
            //         return $("#bill_type").val() == 2;
            //     },
            //     number:true
            // },
            // sgst: {
            //     required: function(element){
            //         return $("#bill_type").val() == 2;
            //     },
            //     number:true
            // },
            // igst: {
            //     required: function(element){
            //         return $("#bill_type").val() == 2;
            //     },
            //     number:true
            // },
            total_amount: {
                required:true,
                number:true
            },
            bill_date: {
                required: true,
            },
            'product[]': {
                required: true,
            },
            'quantity[]': {
                required: true,
                digits: true,
            },
            'rate[]': {
                required: true,
                digits: true,
            },
            'pr_amount[]': {
                required: true,
                digits: true,
            },
        },
        messages: {
            bill_no: {
                required: "Please enter Bill No",
            },
            vendor: {
                required: "Please select Vendor",
            },
            bill_type: {
                required: "Please select bill type",
            },
            bank_account: {
                required: "Please Bank Account",
                            },
            amount :{
                required: "Please enter amount",
            },
            gst: {
                required: "Please Enter GST",
            },
            cgst: {
                required: "Please enter cgst",
            },
            igst: {
                required: "Please enter igst",
            },
            sgst: {
                required: "Please enter sgst",
            },
            total_amount: {
                required: "Please enter total amount"
            },
            bill_date: {
                required: "Please enter Bill Date",
            },
            'product[]': {
                required: "Please select Product",
            },
            'quantity[]': {
                required: "Please enter quantity",
            },
            'rate[]': {
                required: "Please enter rate",
            },
            'pr_amount[]': {
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
