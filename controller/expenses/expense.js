$(function () {
    'use strict';
    $('#expense').submit(function(data){
        data.preventDefault();
        var form = document.getElementById('expense');
        if(form.checkValidity()){
            $.ajax({
                type:'post',
                url:'../../model/expenses/expense.php',
                data:{
                    moveId     :$('#mainItem').val(),
                    moveLineId :$('#subItem').val(),
                    type       :$('#type').val(),
                    state      :$('#statement').val(),
                    price      :$('#amount').val()
                },
                success:function(){
                    // window.location = "../dashboard/dashboard.php";

                }
            })
        }
    })
    $('#mainItem').on('change',function(){
        $.ajax({
            type:'post',
            url:'../../model/expenses/expenseChange.php',
            data:{parent :$('#mainItem').val()},
            success:function(data){
                $('#subItem').find('option').remove().end()
                $('#subItem').append(`<option disabled selected value="">${'إختار نوع'}</option>`)
                $.each(data, function(index){
                    $('#subItem').append(`<option value="${data[index].id}">${data[index].name}</option>`)
                })
            }
        })
    })
})