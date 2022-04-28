$(function () {
	'use strict'
    var which
    $('#user').on('change',function(){
        $.ajax({
            type:'post',
            url:'../../model/settings/permissions/getPermission.php',
            data:{userId : $('#user').val()}
        }).done(function(result){
            var html = ''
            $('#permission').find('option').remove().end()
            $('#permission').append(`<option disabled selected value="">${'إختار الصلاحية'}</option>`)
            $.each(result, function(index){
                if(result[index].userPermission != 0){
                    html += `<tr>
                            <td>${result[index].name}</td>
                            <td><i class='fa fa-remove remove' id=${result[index].userPermission}></td>
                            </tr>`
                }
                else{
                    $('#permission').append(`<option value=${result[index].id}>${result[index].name}</option>`)
                }
            })
            $('tbody').html(html)
        })
    })
    $("button").click(function () {
        which = $(this).attr("id");
    });
    $('#permissions').submit(function(data){
        data.preventDefault()
        if(which == "all"){
            var arr = $('#permission option').map(function(){
                return this.value
            })
            var objectA = []
            for (let index = 0; index < arr.length; index++) {
                var feed
                feed = {
                    "id" : arr[index],
                }
                objectA.push(feed)
            }
            if(parseInt(objectA.length) != 0){
                $.ajax({
                    type:'post',
                    url:'../../model/settings/permissions/all.php',
                    data:{
                        userId : $('#user').val() ,
                        array  : objectA,
                    },
                }).done(function(){
                    window.location.reload()
                })
            } 
        }
        else{
            var form = document.getElementById('permissions')
            if(form.checkValidity()){
                $.ajax({
                    type:'post',
                    url:'../../model/settings/permissions/add.php',
                    data:{
                        userId       : $('#user').val() ,
                        permissionId : $('#permission').val(),
                    },
                }).done(function(){
                    window.location.reload()
                })
            }
        }
    })
    $('body').on('click' , '.remove' , function(){
        var userPermissionId = $(this).attr("id")
        $('#userPermissionId').val(userPermissionId)

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
                        url:'../../model/settings/permissions/permissionDelete.php',
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