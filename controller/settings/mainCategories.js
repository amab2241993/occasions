$(function () {
	'use strict'
    var quantity
    $('#first').DataTable({
        "order": [[ 1, "desc" ]],
        "language": {
            "sProcessing": "جارٍ التحميل...",
            "sLengthMenu": "أظهر _MENU_ مدخلات",
            "sZeroRecords": "لم يعثر على أية سجلات",
            "sInfo": "إظهار _START_ إلى _END_ من أصل _TOTAL_ مدخل",
            "sInfoEmpty": "يعرض 0 إلى 0 من أصل 0 سجل",
            "sInfoFiltered": "(منتقاة من مجموع _MAX_ مُدخل)",
            "sInfoPostFix": "",
            "sSearch": "ابحث:",
            "sUrl": "",
            "oPaginate": {
                "sFirst": "الأول",
                "sPrevious": "السابق",
                "sNext": "التالي",
                "sLast": "الأخير"
            }
        }
    })
    $('#mainCategory').submit(function(data){
        data.preventDefault()
        var form = document.getElementById('mainCategory')
        if(form.checkValidity()){
            quantity = $('#quantity').val() == "" ? 0 :$('#quantity').val()
            $.ajax({
                type:'post',
                url:'../../model/settings/mainCategories.php',
                data:{
                    name     : $('#name').val(),
                    priceCus : $('#priceCus').val(),
                    priceCom : $('#priceCom').val(),
                    priceEmp : $('#priceEmp').val(),
                    quantity : quantity,
                    store    : $('#store').val(),
                    worker   : $('#worker').val()
                },
                success:function(){
                    window.location.reload()
                    alert("تمت الإضافة بنجاح")
                }
            })
        }
    })

    $('body').on('click' , '.edit' , function(){
        $('#storeServiceId').val($(this).parent().attr('storeServiceId'))
        $('#serviceId').val($(this).attr('serviceId'))
        $('#nameU').val($(this).parent().parent().attr('name'))
        $('#priceCusU').val($(this).parent().parent().attr('priceCu'))
        $('#priceComU').val($(this).parent().attr('priceCo'))
        $('#priceEmpU').val($(this).attr('priceEm'))
        $('#update').modal('show')
    })
    
    $("#updateForm").on('submit',function(data){
        data.preventDefault()
        var form = document.getElementById("updateForm")
        if(form.checkValidity()){
            $.ajax({
                type: 'post',
                url: '../../model/settings/mainUpdate.php',
                data: {
                    serviceId      : $('#serviceId').val(),
                    storeServiceId : $('#storeServiceId').val(),
                    name           : $('#nameU').val(),
                    customerPrice  : $('#priceCusU').val(),
                    companionPrice : $('#priceComU').val(),
                    employeePrice  : $('#priceEmpU').val()
                }
            }).done(function(result){
                if(result != true){
                    alert('لم يتم التعديل')
                }
                else{
                    alert('تم التعديل بنجاح')
                    // window.location.reload()
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
                    alert("كلمة المرور غير صحيحة")
                }
                else{
                    $.ajax({
                        type:'post',
                        url:'../../model/settings/mainDelete.php',
                        data:{
                            serviceId : $('#service_id').val(),
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