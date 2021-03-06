$(function () {
	'use strict';
    $('#controllers').submit(function(data){
        data.preventDefault()
        var form = document.getElementById('controllers');
        if(form.checkValidity()){
            $.ajax({
                type:'post',
                url:'../../model/settings/controllers/controllers.php',
                data:{
                    parent :$('#parent').val(),
                    name   :$('#name').val()
                },
                success:function(){
                    window.location.reload()
                    alert("تم الإضافة بنجاح");
                }
            })
        }
    })
    $('#parent').on('change',function(){
        $.ajax({
            type:'POST',
            url:'../../model/settings/controllers/control.php',
            data:{parent:$('#parent').val()},
            dataType:'json'
        }).done(function(data){
            var html = ''
            $.each(data, function(index){
                html += `<tr><td class="col-1">${data[index].code}</td>
                        <td class="col-1">${data[index].name}</td>`
                html += `<td class="col-1" style="font-size:20px" accountName="${data[index].name}">
                        <i class='fa fa-edit edit pl-2' accountId="${data[index].id}" parentId="${data[index].parent_id}"></i>
                        <i class='fa fa-remove remove pl-2' accountId="${data[index].id}"></i>
                        </td></tr>`
            })
            $('tbody').html(html)
        })
    })
    $('body').on('click' , '.edit' , function(){
        $('#accountId').val($(this).attr('accountId'))
        $('#parentId').val($(this).attr('parentId'))
        $('#accountName').val($(this).parent().attr('accountName'))
        $('#update').modal('show')
    })
    $("#updateForm").on('submit',function(data){
        data.preventDefault()
        var form = document.getElementById("updateForm")
        if(form.checkValidity()){
            $.ajax({
                type:'POST',
                url:'../../model/settings/controllers/testUpdate.php',
                data:{
                    accountId   : $('#accountId').val(),
                    parentId    : $('#parentId').val(),
                    accountName : $('#accountName').val()
                }
            }).done(function(result){
                if(result == true){
                    alert("هذا الاسم موجود مسبقا")
                }
                else{
                    $.ajax({
                        type: 'post',
                        url: '../../model/settings/controllers/updateController.php',
                        data: {
                            accountId   : $('#accountId').val(),
                            accountName : $('#accountName').val()
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
                window.location.reload()
            })
        }
    })
    $('body').on('click' , '.remove' , function(){
        var accountId = $(this).attr("accountId")
        $('#accId').val(accountId)

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
                        url:'../../model/settings/controllers/controllerDelete.php',
                        data:{
                            accountId : $('#accId').val(),
                        }
                    }).done(function(){
                        if(result != true){
                            alert("لم يتم الحذف")
                        }
                        else{
                            alert("تم الحذف بنجاح")
                        }
                    })
                }
                window.location.reload()
            })
        }
    })
})