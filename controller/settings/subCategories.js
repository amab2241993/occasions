$(function () {
	'use strict';
    var quantitys;
    $('#subCategory').submit(function(data){
        data.preventDefault()
        var form = document.getElementById('subCategory');
        if(form.checkValidity()){
            quantitys = $('#quantity').val() == "" ? 0 :$('#quantity').val()
            $.ajax({
                type:'post',
                url:'../../model/settings/subCategories.php',
                data:{
                    serviceId : $("#serId").val(),
                    parent    : $("#serId").find('option:selected').attr('parent'),
                    name      : $('#name').val(),
                    quantity  : quantitys,
                },
                cache : false,
                success:function(){
                    window.location.reload()
                    alert("تمت الإضافة بنجاح");
                }
            })
        }
    })

    $('#serId').on('change',function(){
        $.ajax({
            type:'POST',
            url:'../../model/settings/subCategory.php',
            data:{parent:$('#serId').val()},
            dataType:'json',
            cache: false
        }).done(function(data){
            var html = ''
            $.each(data, function(index){
                html += `<tr name="${data[index].category}"><td class='col-1'>${index + 1}</td>`
                html += `<td class="col-2">${data[index].category}</td>`
                html += `<td class="col-2">${data[index].customer_price}</td>`
                html += `<td class="col-2">${data[index].companion_price}</td>`
                html += `<td class="col-2">${data[index].employee_price}</td>`
                html += `<td class="col-2">${data[index].quantity}</td>`
                html += `<td class="col-1" style="font-size:20px">
                        <i class='fa fa-edit edit' serviceId=${data[index].serviceId} ></i>
                        <i class='fa fa-remove remove' serviceId=${data[index].serviceId} ></i></td></tr>`
            })
            $('tbody').html(html)
        })
    })

    $('body').on('click' , '.edit' , function(){
        $('#serviceId').val($(this).attr('serviceId'))
        $('#nameU').val($(this).parent().parent().attr('name'))
        $('#update').modal('show')
    })
    
    $("#updateForm").on('submit',function(data){
        data.preventDefault()
        var form = document.getElementById("updateForm")
        if(form.checkValidity()){
            $.ajax({
                type: 'post',
                url: '../../model/settings/subUpdate.php',
                data: {
                    serviceId : $('#serviceId').val(),
                    name      : $('#nameU').val(),
                }
            }).done(function(result){
                if(result != true){
                    alert('لم يتم التعديل')
                }
                else{
                    alert('تم التعديل بنجاح')
                    window.location.reload()
                }
            })
        }
    })
    $('body').on('click' , '.remove' , function(){
        var serviceId = $(this).attr("serviceId")
        $('#service_id').val(serviceId)

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
                    // alert("كلمة المرور غير صحيحة")
                }
                else{
                    $.ajax({
                        type:'post',
                        url:'../../model/settings/subDelete.php',
                        data:{
                            serviceId : $('#service_id').val(),
                        }
                    }).done(function(){
                        if(result != true){
                        }
                        else{
                            // alert("تم الحذف بنجاح")
                            // window.location.reload()
                        }
                    })
                }
            })
        }
    })
})