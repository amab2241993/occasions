$(function () {
	'use strict';
    $('.users').submit(function(data){
        data.preventDefault()
        $.ajax({
            type:'post',
            url:'../../model/settings/users.php',
            data:{
                name:$('#name').val(),
                pass:$('#pass').val(),
                full:$('#full').val(),
            },
            success:function(){
                window.location = "users.php";
            }
        });
    });
    $('.update').submit(function(data){
        data.preventDefault()
        var index = 1;
        $('button[id="click[]"]').map(function (){
            $(this).on('click' , function(){
                if(index == 1){
                    var x = this.value;
                    $.ajax({
                        type:'post',
                        url:'../../model/settings/updateUsers.php',
                        data:{
                            name   :$('#name' + x).val(),
                            full   :$('#full' + x).val(),
                            userId :$('#user' + x).val(),
                        },
                        success:function(){
                            window.location = "users.php";
                        }
                    });
                }
                index++; 
            })
        }).get();
    });
});