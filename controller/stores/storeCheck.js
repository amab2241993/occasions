$(function () {
    'use strict'
    onload = function(){
        $('table').hide()
    }
    $('#stores').on('change' , function(){
        $.ajax({
            type:'post',
            url:'../../model/stores/storeCheck.php',
            data:{
                storeId : $('#stores').val(),
            },
            cache:false
        }).done(function(data){
            $('tbody').find('tr').remove().end()
            $.each(data, function(index){
                $('tbody').append(
                    `<tr> 
                        <td>${data[index].name}</td>
                        <td>${data[index].quantity}</td>
                    </tr>`
                )
            })
            if($('table').is(":hidden")){
                $('table').show()
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
            }
        })
    })
})