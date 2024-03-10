$(document).ready(function () {

    $.validator.addMethod("noDigits", function (value, element) {
        return this.optional(element) || value != value.match(/^[0-9]*/);
    }, "Only Numbers Not Allowed");

    
    $(".add-production-form").validate({
        ignore: "input[type=hidden]",
        errorElement: "span",
        errorClass: "text-danger",
        debug: true,
        rules: {
            product: {
                required: true,
            },
            quantity: {
                required: true,
            },
            certified: {
                required: true, 
            }
        },
        messages: {
            product_name: {
                required: "Please select product",
            },
            quantity: {
                required: "Quantity type is required",
            },
            certified: {
                required: "certified is required",
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
            
            var flag = 1;
            $('.material').each(function(){
                if($(this).val() == '')
                {
                    flag = flag +1;
                    $(this).parent().append('<span id="material-error" class="text-danger" style="">Material is required</span>');
                    //alert('please select material');
                }
            });
            $('.quantity').each(function(){
                if($(this).val() == '')
                {
                    flag = flag +1;
                    $(this).parent().append('<span id="material-error" class="text-danger" style="">Quantity is required</span>');
                }
            });
            if(flag == 1)
            {
                $(form).find('button[type="submit"]').attr("disabled", "disabled");
                form.submit();
            }
            
        },
    });
});
