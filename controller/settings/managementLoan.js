$(function () {
	'use strict'
    $('#loans').submit(function(data){
        data.preventDefault()
        var form = document.getElementById('loans')
        if(form.checkValidity()){
            $.ajax({
                type:'post',
                url:'../../model/settings/managementLoan/testLoan.php',
                data:{
                    name:$('#name').val(),
                }
            }).done(function(result){
                if(result){
                    alert("هذا الاسم مستخدم مسبقا")
                    window.location.reload()
                }
                else{
                    $.ajax({
                        type:'post',
                        url:'../../model/settings/managementLoan/loans.php',
                        data:{
                            name     : $('#name').val(),
                            phone    : $('#phone').val(),
                            address  : $('#address').val(),
                        }
                    }).done(function(){
                        alert("تم بنجاح")
                        window.location.reload()
                    })
                }
            })
        }
    })
    $('body').on('click' , '.edit' , function(){
        $('#loanN').val($(this).parent().parent().attr('loan'))
        $('#loanP').val($(this).parent().parent().attr('phone'))
        $('#loanA').val($(this).parent().attr('address'))
        $('#loanId').val($(this).attr('id'))
        $('#update').modal('show')
    })
    $("#updateForm").on('submit',function(data){
        data.preventDefault()
        var form = document.getElementById("updateForm")
        if(form.checkValidity()){
            $.ajax({
                type:'POST',
                url:'../../model/settings/managementLoan/testUpdate.php',
                data:{
                    loanId : $('#loanId').val(),
                    loanN  : $('#loanN').val(),
                }
            }).done(function(result){
                if(result == true){
                    alert("هذا الاسم موجود مسبقا")
                }
                else{
                    $.ajax({
                        type: 'post',
                        url: '../../model/settings/managementLoan/updateLoan.php',
                        data: {
                            loanId : $('#loanId').val(),
                            loanN  : $('#loanN').val(),
                            loanP  : $('#loanP').val(),
                            loanA  : $('#loanA').val()
                        }
                    }).done(function(result){
                        if(result != true){
                            alert('لم يتم التعديل')
                            window.location.reload()
                        }
                        else{
                            alert('تم التعديل بنجاح')
                            window.location.reload()
                        }
                    })
                }
            })
        }
    })
    $('body').on('click' , '.remove' , function(){
        var loanId = $(this).attr("id")
        $('#loan_id').val(loanId)

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
                        url:'../../model/settings/managementLoan/loanDelete.php',
                        data:{
                            loanId : $('#loan_id').val(),
                        }
                    }).done(function(result){
                        if(result != true){
                            alert("لم يتم الحذف")
                            window.location.reload()
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