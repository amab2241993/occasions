$(function () {
	'use strict';
    $('.login').submit(function(data){
        data.preventDefault()
        $.ajax({
            type:'post',
            url:'../../model/login.php',
            data:{
                user:$('#user').val(),
                pass:$('#pass').val(),
            },
            success:function(){
                window.location = "../dashboard/dashboard.php";
            }
        });
    });
});