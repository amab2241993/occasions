$(function () {
    'use strict'
    $('.js-example-basic-single').select2()
    
    $('#incentives').on('keyup' , function(){
        var incentive = $('#incentives').val()
        if(incentive == "" || incentive == null || incentive == 0){
            incentive = 0
            $('#incentives').val(incentive)
        }
        countting()
    })
    
    $('#relayIn').on('keyup' , function(){
        var relayIn = $('#relayIn').val()
        if(relayIn == "" || relayIn == null || relayIn == 0){
            relayIn = 0
            $('#relayIn').val(relayIn)
        }
        countting()
    })
    
    $('#relayOut').on('keyup' , function(){
        var relayOut = $('#relayOut').val()
        if(relayOut == "" || relayOut == null || relayOut == 0){
            relayOut = 0
            $('#relayOut').val(relayOut)
        }
        countting()
    })
    
    $('#driver').on('keyup' , function(){
        var driver = $('#driver').val()
        if(driver == "" || driver == null || driver == 0){
            driver = 0
            $('#driver').val(driver)
        }
        countting()
    })
    function countting(){
        var total =  parseInt($('#tent').text())    + parseInt($('#electricity').text())
            total += parseInt($('#service').text()) + parseInt($('#decoration').text())
            total += parseInt($('#relayOut').val()) + parseInt($('#administrative').text())
            total += parseInt($('#admin').text())   + parseInt($('#warehouse').text())
            total += parseInt($('#relayIn').val())  + parseInt($('#incentives').val())
            total += parseInt($('#driver').val())   + parseInt($('#companion').text())
        $('#expense').text(total)
        $('#profits').text(parseInt($('#profit').val()) - parseInt(total))
        $('#money').text(parseInt($('#moneys').val())   - parseInt(total))
    }
    $('#save').on('click' , function(){
        $.ajax({
            type:'post',
            url:'../../model/bills_expenses/billExpense.php',
            data:{
                relayIn    : $('#relayIn').val(),
                relayOut   : $('#relayOut').val(),
                incentives : $('#incentives').val(),
                driver     : $('#driver').val(),
                total      : $('#expense').text(),
                counter    : $('#counter').val(),
                mainId     : $('#mainId').val(),
            }
        }).done(function(){
            // window.location.reload()
        })
    })
})