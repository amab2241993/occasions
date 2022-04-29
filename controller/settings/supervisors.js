$(function () {
    'use strict'
    $('.cost').on('keyup' , function(){
        if($(this).val().charAt(0) == '%'){
            $(this).val($(this).val())
        }
        else{
            $(this).val("%" + $(this).val())
        }
    })
    $(".click").on("click", function(){
        var id = $('input[name="data[]"]').map(function(){
            return $(this).attr('id')
        })

        var cost = $('input[name="data[]"]').map(function(){
            return this.value
        })
        var objectA = []
        for (let index = 0; index < id.length; index++) {
            var feed
            feed = {
                "id"   : id[index],
                "cost" : cost[index]
            }
            objectA.push(feed)
        }
        $.ajax({
            type:'post',
            url:'../../model/settings/supervisors/supervisors.php',
            data:{details : objectA}
        }).done(function(){
            alert('تم التعديل بنجاح')
            window.location.reload()
        })
    })
})