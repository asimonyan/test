$(document).ready(function(){

    $("#messageForm").validate({

        rules:{

            name:{
                required: true,
                minlength: 4,
                maxlength: 16
            },

            email:{
                required: true,
                email: true
            },
            message:{
                required: true,
                minlength: 10,
                maxlength: 100
            }
        },
        messages:{

            name:{
                required: "This value should not be blank",
                minlength: "Name must be at least 4 characters long",
                maxlength: "Name cannot be longer than 16 characters"
            },

            email:{
                required: "This value should not be blank",
                email: "The email  is not a valid"
            },
            message:{
                required: "This value should not be blank",
                minlength: "Message must be at least 10 characters long",
                maxlength: "Message cannot be longer than 100 characters"
            }

        }

    });

});