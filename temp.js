$(function () {
    'use strict'
    $('td[name="delete[]"]').on('click' , function(){
        var index           = $('td[name="delete[]"]').index(this)
        var priceWorkers    = $('#price_workers'   + index).text()
        var totalUnits      = $('#total_units'     + index).text()
        var baggageOld      = $('#baggage').text()
        var workersOld      = $('#workers').text()
        var remainingOld    = $('#remaining').val()
        var numDaysOld      = $('#num_days').val()
        var discountOld     = $('#discount').val()
        var totalOld        = $('#total').text()
        var baggageHideOld  = $('#baggageHide').val()
        if(discountOld == "" || discountOld == null ){
            discountOld  = 0
        }
        if(numDaysOld == "" || numDaysOld == null ){
            numDaysOld  = 0
        }
        var baggageNew      =  parseInt(baggageOld)     - (parseInt(totalUnits) * parseInt(numDaysOld))
        var workersNew      =  parseInt(workersOld)     - parseInt(priceWorkers)
        var remainingNew    =  parseInt(remainingOld)   - (parseInt(totalUnits) * parseInt(numDaysOld))
        remainingNew        += parseInt(discountOld)    - parseInt(priceWorkers)
        var totalNew        =  parseInt(totalOld)       - (parseInt(totalUnits) * parseInt(numDaysOld))
        var baggageHideNew  =  parseInt(baggageHideOld) - parseInt(totalUnits)
        totalNew            -= parseInt(priceWorkers)
        $('#baggage').text(baggageNew)
        $('#workers').text(workersNew)
        $('#remaining').val(remainingNew)
        $('#total').text(totalNew)
        $('#baggageHide').val(baggageHideNew)
        $('#percentage_discount').val(0)
        $('#discount').val(0)
        var x = $("tbody").children().length
        var y = $('tbody > tr').filter(function() {
            return $(this).css('display') === 'none';
        }).length
        var result = parseInt(x - y) - 1
        for (let counter = index; counter < result; counter++) {
            $('#id_numbers'      + counter).text($('#id_numbers'     + (counter + 1)).text())
            $('#item'            + counter).text($('#item'           + (counter + 1)).text())
            $('#quantities'      + counter).val($('#quantities'      + (counter + 1)).val())
            $('#quantityHide'    + counter).val($('#quantityHide'    + (counter + 1)).val())
            $('#price_unit'      + counter).text($('#price_unit'     + (counter + 1)).text())
            $('#price_worker'    + counter).text($('#price_worker'   + (counter + 1)).text())
            $('#itemHide'        + counter).val($('#itemHide'        + (counter + 1)).val())
            $('#idNumbersHide'   + counter).val($('#idNumbersHide'   + (counter + 1)).val())
            $('#price_workers'   + counter).text($('#price_workers'  + (counter + 1)).text())
            $('#total_units'     + counter).text($('#total_units'    + (counter + 1)).text())
            $('#worker'          + counter).text($('#worker'         + (counter + 1)).val())
            $('#store'           + counter).text($('#store'          + (counter + 1)).val())
        }
        $('#id_numbers'      + result).text(0)
        $('#item'            + result).text(0)
        $('#quantities'      + result).val(0)
        $('#quantityHide'    + result).val(0)
        $('#price_unit'      + result).text(0)
        $('#price_worker'    + result).text(0)
        $('#price_workers'   + result).text(0)
        $('#total_units'     + result).text(0)
        $('#worker'          + result).val(0)
        $('#store'           + result).val(0)
        $('tbody > tr').eq(result).hide();
        if(result == 0){
            $('#baggage').text(0)
            $('#workers').text(0)
            $('#remaining').val(0)
            $('#total').text(0)
            $('#baggageHide').val(0)
            $('#percentage_discount').val(0)
            $('#discount').val(0)
            $('#num_days').val(1)
            $('#relay').val(0)
            $('#worker' + result).val(0)
            $('#store'  + result).val(0)
            $('table').hide()
            $('#bockings').hide()
        }
    })
    $('#bockings').submit(function(data){
        data.preventDefault()
        var form = document.getElementById('bockings')
        if(form.checkValidity()){
            var idNumbers1 = $('td[name="id_numbers[]"]').map(function(){
                if($(this).text() != 0)return $(this).text()
            })
            var item1 = $('td[name="item[]"]').map(function(){
                if($(this).text() != 0)return $(this).text()
            })
            var quantities1 = $('input[name="quantities[]"]').map(function(){
                if($(this).val() != 0)return $(this).val()
            })
            var priceUnit1 = $('td[name="price_unit[]"]').map(function(){
                if($(this).text() != 0)return $(this).text()
            })
            var priceWorker1 = $('td[name="price_worker[]"]').map(function(){
                if($(this).text() != 0)return $(this).text()
            })
            var priceWorkers1 = $('td[name="price_workers[]"]').map(function(){
                if($(this).text() != 0)return $(this).text()
            })
            var totalUnits1 = $('td[name="total_units[]"]').map(function(){
                if($(this).text() != 0)return $(this).text()
            })
            var worker1 = $('input[name="worker[]"]').map(function(){
                if($(this).val() != 0)return $(this).val()
            })
            var store1 = $('input[name="store[]"]').map(function(){
                if($(this).val() != 0)return $(this).val()
            })
            var objectA = []
            for (let index = 0; index < idNumbers1.length; index++) {
                var feed;
                if($('#type').val() != 3){
                    feed = {
                        "id"           : index + 1,
                        "serviceId"    : idNumbers1[index],
                        "serviceName"  : item1[index],
                        "quantity"     : quantities1[index],
                        "priceUnit"    : priceUnit1[index],
                        "priceWorker"  : priceWorker1[index],
                        "priceWorkers" : priceWorkers1[index],
                        "totalUnits"   : totalUnits1[index],
                        "workerId"     : worker1[index],
                        "storeId"      : store1[index],
                    }
                }
                else{
                    feed = {
                        "id"           : index + 1,
                        "serviceId"    : idNumbers1[index],
                        "serviceName"  : item1[index],
                        "quantity"     : quantities1[index],
                        "priceUnit"    : priceUnit1[index],
                        "totalUnits"   : totalUnits1[index],
                        "workerId"     : worker1[index],
                        "storeId"      : store1[index],
                    }
                }
                objectA.push(feed)
            }
            $.ajax({
                type:'post',
                url:'../../model/bockings/bocking.php',
                data:{
                    customerId  : $('#customer').val(),
                    billDate    : $('#date').val(),
                    numDays     : $('#num_days').val(),
                    baggage     : $('#baggage').text(),
                    total_price : $('#total').text(),
                    discount    : $('#discount').val(),
                    relay       : $('#relay').val(),
                    empPrice    : $('#workers').text(),
                    billType    : $('#type').val(),
                    price       : $('#remaining').val(),
                    details     : objectA
                }
            }).done(function(){
                window.location = "../bockings/bockingFirst.php";
            })
        }
    })
})