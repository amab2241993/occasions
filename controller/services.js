$(function () {
	'use strict';
    $('.services').submit(function(data){
        data.preventDefault()
        var forms = document.getElementsByClassName('services');
        Array.prototype.filter.call(forms, function(form) {
            if(form.checkValidity()){
                $.ajax({
                    type:'post',
                    url:'model/services.php',
                    data:{
                        name :$('#name').val(),
                        price:$('#price').val(),
                        parent:$('#parent').val()
                    },
                    success:function(){
                        window.location.reload()
                    }
                })
            }
        })
    })
    $('.update').submit(function(data){
        data.preventDefault()
        var index = 1;
        $('button[id="click[]"]').map(function (){
            $(this).on('click' , function(){
                if(index == 1){
                    var x = this.value;
                    $.ajax({
                        type:'post',
                        url:'model/updateServices.php',
                        data:{
                            name      :$('#name' + x).val(),
                            price     :$('#price' + x).val(),
                            serviceId :$('#service' + x).val(),
                        },
                        success:function(){
                            window.location = "services.php";
                        }
                    });
                }
                index++; 
            })
        }).get();
    });
});