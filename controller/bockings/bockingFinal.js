$(function () {
    'use strict'
    $('#first').DataTable({
        "order": [[ 0, "desc" ]],
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
                    billId : $('#billId' + x).val(),
                    move   : $('#move'   + x).val(),
                    type   : $('#type'   + x).val(),
                    price  : $('#price'  + x).val(),
                    money  : parseInt($('#price' + x).val()) + parseInt($('#money'+ x).val()),
                }
            }).done(function(info){
                var data = $.parseJSON(info)
                if(data[0].status != 100){
                    alert(data[0].message)
                }
                else{
                    $.ajax({
                        type: "POST",
                        url: "../../printer/dashboard/bocking.php",
                        async:false,
                        data:{
                            billId : data[0].billId,
                            status : 2
                        },
                        success: function(data) {
                            $(data).printThis({
                                debug: false,               // show the iframe for debugging
                                importCSS: true,            // import parent page css
                                importStyle: true,         // import style tags
                                printContainer: true,       // print outer container/$.selector
                                loadCSS: "",                // path to additional css file - use an array [] for multiple
                                pageTitle: "",              // add title to print page
                                removeInline: false,        // remove inline styles from print elements
                                removeInlineSelector: "*",  // custom selectors to filter inline styles. removeInline must be true
                                printDelay: 333,            // variable print delay
                                header: null,               // prefix to html
                                footer: null,               // postfix to html
                                base: false,                // preserve the BASE tag or accept a string for the URL
                                formValues: true,           // preserve input/form values
                                canvas: false,              // copy canvas content
                                doctypeString: '...',       // enter a different doctype for older markup
                                removeScripts: false,       // remove script tags from print content
                                copyTagClasses: false,      // copy classes from the html & body tag
                                beforePrintEvent: null,     // function for printEvent in iframe
                                beforePrint: null,          // function called before iframe is filled
                                afterPrint: function(){
                                    window.location.reload()
                                }
                            })
                        }
                    })
                }
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