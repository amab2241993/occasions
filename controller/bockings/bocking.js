$(function () {
    'use strict'
    onload = function(){
        $('table').hide()
        $('#bockings').hide()
        $.ajax({
            type:'post',
            url:'../../model/bockings/bockings.php',
            data:{customerId:$('#type').val()}
        }).done(function(data){
            $('#customer').find('option').remove().end()
            $('#customer').append(`<option disabled selected value="">${'إختار العميل'}</option>`)
            $.each(data, function(index){
                $('#customer').append(`<option value="${data[index].id}">${data[index].name}</option>`)
            })
        })
    }
})