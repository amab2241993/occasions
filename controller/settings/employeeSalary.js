$(function () {
	'use strict'
    $('#employees').submit(function(data){
        data.preventDefault()
        var form = document.getElementById('employees')
        if(form.checkValidity()){
            $.ajax({
                type:'post',
                url:'../../model/settings/employees/salaryUpdate.php',
                data:{
                    employeeId : $('#employee').val(),
                    salary     : $('#salary').val()
                }
            }).done(function(){
                // window.location.reload()
            })
        }
    })
    $('body').on('click' , '.edit' , function(){
        $('#employeeN').val($(this).parent().parent().attr('name'))
        $('#salaryNew').val($(this).parent().parent().attr('salary'))
        $('#employeeId').val($(this).attr('id'))
        $('#update').modal('show')
    })
    $("#updateForm").on('submit',function(data){
        data.preventDefault()
        var form = document.getElementById("updateForm")
        if(form.checkValidity()){
            $.ajax({
                type: 'post',
                url: '../../model/settings/employees/salaryUpdate.php',
                data: {
                    employeeId : $('#employeeId').val(),
                    salary     : $('#salaryNew').val(),
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
    })
    $('body').on('click' , '.remove' , function(){
        var employeeId = $(this).attr("id")
        $('#employee_id').val(employeeId)

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
                        url:'../../model/settings/employees/salaryUpdate.php',
                        data:{
                            employeeId : $('#employee_id').val(),
                            salary     : 0
                        }
                    }).done(function(){
                        if(result != true){
                        }
                        else{
                            alert("تم الحذف بنجاح")
                            // window.location.reload()
                        }
                    })
                }
            })
        }
    })
})