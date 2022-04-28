$(function () {
	'use strict'
    $('#user').on('change',function(){
        var userId = $('#user').val()
        $.ajax({
            type:'post',
            url:'../../model/settings/permissions/getPermission.php',
            data:{userId : userId}
        }).done(function(result){
            var html = ''
            $('#permission').find('option').remove().end()
            $('#permission').append(`<option disabled selected value="">${'إختار الصلاحية'}</option>`)
            var arr = []
            var name = ''
            var counter = 0
            $.each(result, function(index){
                if(result[index].user_id == userId){
                    html += `<tr><td>${result[index].name}</td><td><i class='fa fa-remove delete'></td>
                    </tr>`
                }
                $.each(result , function(key){
                    if(name != result[index].name)
                    if(result[key].name == result[index].name){
                        counter++;
                    }
                })
                if(counter == 1){
                    $('#permission').append(
                        `<option value="${result[index].permissionId}">
                        ${result[index].name}</option>`
                    )
                }
            })
            console.log(arr)
            $('tbody').html(html)
        })
    })
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