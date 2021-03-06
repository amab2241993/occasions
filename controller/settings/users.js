$(function () {
	'use strict'
    $('#users').submit(function(data){
        data.preventDefault()
        var form = document.getElementById('users')
        if(form.checkValidity()){
            $.ajax({
                type:'post',
                url:'../../model/settings/users/testUser.php',
                data:{name:$('#name').val()}
            }).done(function(result){
                if(result){
                    alert("هذا الاسم مستخدم مسبقا")
                }
                else{
                    $.ajax({
                        type:'post',
                        url:'../../model/settings/users/users.php',
                        data:{
                            name:$('#name').val(),
                            pass:$('#pass').val(),
                            full:$('#full').val(),
                        }
                    }).done(function(){
                        window.location.reload()
                    })
                }
            })
        }
    })
    $('body').on('click' , '.edit' , function(){
        $('#userName').val($(this).parent().parent().attr('user'))
        $('#fullName').val($(this).parent().parent().attr('full'))
        $('#thisName').val($(this).parent().parent().attr('user'))
        $('#userId').val($(this).attr('id'))
        $('#update').modal('show')
    })
    $("#updateForm").on('submit',function(data){
        data.preventDefault()
        var form = document.getElementById("updateForm")
        if(form.checkValidity()){
            $.ajax({
                type:'POST',
                url:'../../model/settings/users/testUpdate.php',
                data:{
                    userId   : $('#userId').val(),
                    userName : $('#userName').val(),
                }
            }).done(function(result){
                if(result == true){
                    alert("هذا الاسم موجود مسبقا")
                }
                else{
                    $.ajax({
                        type: 'post',
                        url: '../../model/settings/users/updateUsers.php',
                        data: {
                            userId   : $('#userId').val(),
                            userName : $('#userName').val(),
                            fullName : $('#fullName').val(),
                            thisName : $('#thisName').val()
                        }
                    }).done(function(result){
                        if(result != true){
                            alert('لم يتم التعديل')
                        }
                        else{
                            alert('تم التعديل بنجاح')
                        }
                    })
                }
                // window.location.reload()
            })
        }
    })
    $('body').on('click' , '.remove' , function(){
        var userId = $(this).attr("id")
        $('#user_id').val(userId)

        if(confirm("هل انت متأكد من انك تريد الحزف")){
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
                        url:'../../model/settings/users/userDelete.php',
                        data:{
                            userId : $('#user_id').val(),
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