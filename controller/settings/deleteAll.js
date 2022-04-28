$(function () {
	'use strict'
    var which
    $('#remove').on('click' , function(){
        if(confirm("هل انت متأكد من انك تريد الحذف جميع العمليات")){
            $('#passwordInter').modal('show')
        }
    })
    $("#passowrdForm").on('submit',function(data){
        data.preventDefault()
        var form = document.getElementById("passowrdForm")
        if(form.checkValidity()){
            $.ajax({
                type:'post',
                url:'../../model/bockings/testPassword.php',
                data:{password : $('#password').val()}
            }).done(function(result){
                if(result != true){
                    alert("كلمة المرور غير صحيحة")
                }
                else{
                    $.ajax({
                        type:'post',
                        url:'../../model/settings/deleteAll.php',
                        data:{
                            userPermissionId : $('#userPermissionId').val(),
                        }
                    }).done(function(){
                        if(result != true){
                        }
                        else{
                            alert("تم الحذف بنجاح")
                            window.location.reload()
                        }
                    })
                }
            })
        }
    })
})