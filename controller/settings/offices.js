$(function () {
	'use strict'
    $('#offices').submit(function(data){
        data.preventDefault()
        var form = document.getElementById('offices')
        if(form.checkValidity()){
            $.ajax({
                type:'post',
                url:'../../model/settings/offices/testOffices.php',
                data:{
                    name:$('#name').val(),
                    status : 2
                }
            }).done(function(result){
                if(result){
                    alert("هذا الاسم مستخدم مسبقا")
                }
                else{
                    $.ajax({
                        type:'post',
                        url:'../../model/settings/offices/offices.php',
                        data:{
                            name  : $('#name').val(),
                            phone : $('#phone').val(),
                        }
                    }).done(function(){
                        window.location.reload()
                    })
                }
            })
        }
    })
    $('body').on('click' , '.edit' , function(){
        $('#customerName').val($(this).parent().parent().attr('customer'))
        $('#customerPhone').val($(this).parent().parent().attr('phone'))
        $('#customerId').val($(this).attr('id'))
        $('#update').modal('show')
    })
    $("#updateForm").on('submit',function(data){
        data.preventDefault()
        var form = document.getElementById("updateForm")
        if(form.checkValidity()){
            $.ajax({
                type:'POST',
                url:'../../model/settings/offices/testUpdate.php',
                data:{
                    customerId     : $('#customerId').val(),
                    customerName   : $('#customerName').val(),
                    customerStatus : 2
                }
            }).done(function(result){
                if(result == true){
                    alert("هذا الاسم موجود مسبقا")
                }
                else{
                    $.ajax({
                        type: 'post',
                        url: '../../model/settings/offices/updateOffices.php',
                        data: {
                            customerId     : $('#customerId').val(),
                            customerName   : $('#customerName').val(),
                            customerPhone  : $('#customerPhone').val(),
                            customerStatus : 2
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
        var customerId = $(this).attr("id")
        $('#customer_id').val(customerId)

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
                        url:'../../model/settings/offices/officeDelete.php',
                        data:{
                            customerId : $('#customer_id').val(),
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