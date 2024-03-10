$(document).ready(function () {

    $.validator.addMethod("noDigits", function (value, element) {
        return this.optional(element) || value != value.match(/^[0-9]*/);
    }, "Only Numbers Not Allowed");

    
    $(".add-company-form").validate({
        ignore: "input[type=hidden]",
        errorElement: "span",
        errorClass: "text-danger",
        debug: true,
        rules: {
            company_name: {
                required: true,
                minlength: 3,
                maxlength: 100,
                noDigits: true,
            },
            email_id: {
                required: true,
                email: true
            },
            contact_no: {
                required: true,
                number: true    
            },
            mobile_no: {
                required: true,
                minlength: 8,
                maxlength: 12,
                number:true
            },
            contact_person_name :{
                required: true,
                maxlength:100
            },
            // gst_no: {
            //     required:true
            // },
            address: {
                required: true,
                maxlength:250
            },
            country: {
                required: true,
                maxlength: 50
            },
            state: {
                required: true,
                maxlength: 50
            },
            pincode: {
                required: true,
                number:true
            },
            currency: {
                required:true
            },
            pan_no: {
                required: true,
            },
            sufix: {
                required: true,
                maxlength: 3
            },
            wallet_balance:
            {
                required: true,
                number:true
            }
        },
        messages: {
            company_name: {
                required: "Please enter company name",
            },
            email_id: {
                required: "Email id is required",
            },
            contact_no: {
                required: "Please enter contact no",
            },
            mobile_no: {
                required: "Mobile number is required",
                            },
            contact_person_name :{
                required: "Please enter contact person name",
            },
            address: {
                required: "Address is required",
            },
            country: {
                required: "Country is required",
            },
            state: {
                required: "State is required",
            },
            pincode: {
                required: "Pincode is required",
            },
            currency: {
                required: "Currency is required"
            },
            pan_no: {
                required: "PAN is required",
            },
            sufix: {
                required: "Company sufix is required",
            },
            sufix: {
                required: "Wallet balance is required",
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

    $(".add-bank-details-form").validate({
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
            bank_name: {
                required: true,
            },
            ifsc_code: {
                required: true,
            },
            beneficary_name: {
                required: true,
            },
            account_no: {
                required: true,
                number: true
            },
            balance: {
                required: true,
                number: true
            },
        },
        messages: {
            bank_name: {
                required: "Please add Bank name"
            },
            ifsc_code: {
                required: "Please add IFSC code"
            },
            beneficary_name: {
                required: "Please add beneficary name"
            },
            account_no: {
                required: "Please add account name"
            },
            balance: {
                required: "Please add balance"
            }
        },
        submitHandler: function (form) {
            $(form).find('button[type="submit"]').attr("disabled", "disabled");
            form.submit();
        },
    });
});
