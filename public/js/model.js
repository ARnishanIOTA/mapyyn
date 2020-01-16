$(document).ready(function(){
    $('#modelForm').submit(function(e){
        e.preventDefault();
        url = $(this).attr('action');
        data = $(this).serialize();
        $.ajax({
            type : 'POST',
            url : url,
            data : data,
            beforeSend : function(){
                $('#button').attr('disabled','disabled');
                $('#flash').fadeOut();
            },

            success : function(response){
                if(response.invalid != null){
                    swal("", invalidMessage + ' ' + response.invalid + ' ' + time, "error");
                    $('#button').prop('disabled', false);
                    return ;
                }

                if(response == 'invalid_address'){
                    swal("", 'مدينة غير صحيحة', "error");
                    $('#button').prop('disabled', false);
                    return ;
                }
                swal("", successMessage, "success");
                $('#button').prop('disabled', false);
                $('#reset').click();
            },

            error : function(response){
                if(response.responseJSON.password != null){
                    $("#flash").find('div').remove();
                    $('#flash').append("<div class='alert alert-danger'><li>"+response.responseJSON.password+"</li></div>").fadeIn()
                    $('#button').prop('disabled', false);
                    setTimeout(function(){
                        $('#flash').fadeOut();
                    }, 4000)
                }else{
                    errors = response.responseJSON.errors;
                    $("#flash").find('div').remove();
                    $.each(errors, function(key, value) { 
                        $('#flash').append("<div class='alert alert-danger'><li>"+value+"</li></div>").fadeIn()
                    });
                    $('#button').prop('disabled', false);
                    setTimeout(function(){
                        $('#flash').fadeOut();
                    }, 4000) 
    
                    if(response.responseJSON.danger != null){
                        $('#flash').append("<div class='alert alert-danger'><li>"+response.responseJSON.danger+"</li></div>").fadeIn()
                        $('#button').prop('disabled', false);
                        setTimeout(function(){
                            $('#flash').fadeOut();
                        }, 4000)
                    }
                }
                
            }
        });
    })
})  


$(document).ready(function(){
    $('#modelFormSubscribe').submit(function(e){
        e.preventDefault();
        url = $(this).attr('action');
        data = $(this).serialize();
        $.ajax({
            type : 'POST',
            url : url,
            data : data,
            beforeSend : function(){
                $('#buttonSubscribe').attr('disabled','disabled');
                $('#flash').fadeOut();
            },

            success : function(response){
                if(response.invalid != null){
                    swal("", invalidMessage + ' ' + response.invalid + ' ' + time, "error");
                    $('#buttonSubscribe').prop('disabled', false);
                    return ;
                }
                swal("", successMessage, "success");
                $('#buttonSubscribe').prop('disabled', false);
                $('#resetSubscribe').click();
            },

            error : function(response){
                errors = response.responseJSON.errors;
                $("#flash").find('div').remove();
                $.each(errors, function(key, value) { 
                    $('#flash').append("<div class='alert alert-danger'><li>"+value+"</li></div>").fadeIn()
                });
                $('#buttonSubscribe').prop('disabled', false);
                setTimeout(function(){
                    $('#flash').fadeOut();
                }, 4000)
            }
        });
    })
}) 


