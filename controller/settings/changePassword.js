$(function () {
	'use strict'
    $('#changePassword').submit(function(data){
        data.preventDefault()
        var form = document.getElementById('changePassword')
        if(form.checkValidity()){
            $.ajax({
                type:'post',
                url:'../../model/bockings/testPassword.php',
                data:{password : $('#oldPassword').val()}
            }).done(function(result){
                if(result != true){
                    alert("كلمة المرور غير صحيحة")
                }
                else{
                    if($('#newPassword1').val() !== $('#newPassword2').val()){
                        alert("كلمات المرور غير متطابقة")
                    }
                    else{
                        $.ajax({
                            type:'post',
                            url:'../../model/settings/changePassword/changePassword.php',
                            data:{
                                password : $('#newPassword1').val(),
                            }
                        }).done(function(data){
                            if(data == 1)
                            alert("تم التغيير بنجاح")
                            else alert("لم يتم التغير")
                            window.location.reload()
                        })
                    }
                }
            })
        }
    })
})