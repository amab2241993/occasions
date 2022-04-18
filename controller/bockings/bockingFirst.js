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
        x = $(this).attr('id').slice(9)
        var date1 = new Date()
        var date2 = new Date($('#date' + x).val())
        var m1 = date1.getMonth()
        var m2 = date2.getMonth()
        var y1 = date1.getFullYear()
        var y2 = date2.getFullYear()
        if((m2 > m1 && y2 == y1)||y2 > y1){
            $("#typeM" + x).attr({"required" : false})
            var form = document.getElementById("uncertain" + x);
            if(form.checkValidity()){
                $.ajax({
                    type:'post',
                    url:'../../model/bockings/bockingLate.php',
                    data:{
                        res   :$('#res'   + x).val(),
                        main  :$('#main'  + x).val(),
                        type  :$('#type'  + x).val(),
                        price :$('#price' + x).val(),
                    }
                }).done(function(){
                    window.location = "../bockings/bockingLate.php"
                })
            }
        }
        else{
            $("#typeM" + x).attr({"required" : true})
            var form = document.getElementById("uncertain" + x);
            if(form.checkValidity()){
                $.ajax({
                    type:'post',
                    url:'../../model/bockings/bockingFinal.php',
                    data:{
                        res   :$('#res'   + x).val(),
                        main  :$('#main'  + x).val(),
                        type  :$('#type'  + x).val(),
                        typeM :$('#typeM' + x).val(),
                        price :$('#price' + x).val(),
                    }
                }).done(function(){
                    window.location = "../bockings/bockingFinal.php";
                })
            }
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