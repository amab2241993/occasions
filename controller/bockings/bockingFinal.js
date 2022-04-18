$(function () {
    'use strict'
    $('#first').DataTable({
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
    var x = 0
    $('form[name="tester[]"]').submit(function (e){
        e.preventDefault()
        x = $(this).attr('id').slice(7)
        var form = document.getElementById("certain" + x);
        if(form.checkValidity()){
            $.ajax({
                type:'post',
                url:'../../model/bockings/bockingFinalUpdate.php',
                data:{
                    move  : $('#move' + x).val(),
                    type  : $('#type' + x).val(),
                    price : $('#price' + x).val(),
                    money : parseInt($('#price' + x).val()) + parseInt($('#money'+ x).val()),
                }
            }).done(function(){
                window.location.reload()
            })
        }
    })
    $(".deleteBocking").on("click" , function(){
        var mainId = $(this).parent().attr("id")
        var code   = $(this).parent().attr("code")
        var status = $(this).parent().attr("status")
        $('#mainId').val(mainId)
        $('#code').val(code)
        $('#status').val(status)

        if(confirm("هل انت متأكد من انك تريد الحزف")){
            $('#passwordInter').modal('show');
        }
    })
    $("#passowrdForm").on('submit',function(data){
        data.preventDefault()
        var form = document.getElementById("passowrdForm");
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
                        url:'../../model/bockings/delete.php',
                        data:{
                            mainId : $('#mainId').val(),
                            code   : $('#code').val(),
                            status : $('#status').val()
                        }
                    }).done(function(){
                        if(result != true){
                        }
                        else{
                            alert("تم الحذف بنجاح")
                            window.location.reload();
                        }
                    })
                }
            })
        }
    })
})