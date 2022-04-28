$(function () {
	'use strict'
    $('#employees').submit(function(data){
        data.preventDefault()
        var form = document.getElementById('employees')
        if(form.checkValidity()){
            $.ajax({
                type:'post',
                url:'../../model/settings/employees/testEmployee.php',
                data:{
                    name:$('#name').val(),
                }
            }).done(function(result){
                if(result){
                    alert("هذا الاسم مستخدم مسبقا")
                }
                else{
                    $.ajax({
                        type:'post',
                        url:'../../model/settings/employees/employees.php',
                        data:{
                            name     : $('#name').val(),
                            phone    : $('#phone').val(),
                            address  : $('#address').val(),
                            jobTitle : $('#jobTitle').val(),
                        }
                    }).done(function(){
                        window.location.reload()
                    })
                }
            })
        }
    })
    $('body').on('click' , '.edit' , function(){
        $('#employeeN').val($(this).parent().parent().attr('employee'))
        $('#employeeP').val($(this).parent().parent().attr('phone'))
        $('#employeeA').val($(this).parent().attr('address'))
        $('#employeeS').val($(this).parent().attr('statement'))
        $('#employeeId').val($(this).attr('id'))
        $('#update').modal('show')
    })
    $("#updateForm").on('submit',function(data){
        data.preventDefault()
        var form = document.getElementById("updateForm")
        if(form.checkValidity()){
            $.ajax({
                type:'POST',
                url:'../../model/settings/employees/testUpdate.php',
                data:{
                    employeeId : $('#employeeId').val(),
                    employeeN  : $('#employeeN').val(),
                }
            }).done(function(result){
                if(result == true){
                    alert("هذا الاسم موجود مسبقا")
                }
                else{
                    $.ajax({
                        type: 'post',
                        url: '../../model/settings/employees/updateEmployee.php',
                        data: {
                            employeeId : $('#employeeId').val(),
                            employeeN  : $('#employeeN').val(),
                            employeeP  : $('#employeeP').val(),
                            employeeA  : $('#employeeA').val(),
                            employeeS  : $('#employeeS').val()
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
                        url:'../../model/settings/employees/employeeDelete.php',
                        data:{
                            employeeId : $('#employee_id').val(),
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