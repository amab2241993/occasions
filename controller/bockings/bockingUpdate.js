$(function () {
    'use strict'
    onload = function(){
        $("#type").attr({"disabled" : true})
        $("#customer").attr({"disabled" : true})
        for (let index = $('#jsons').val(); index < parseInt($('#remember').val()) ; index++) {
            $('tbody > tr').eq(index).hide();
        }
    }
    $('#bocking').submit(function(data){
        data.preventDefault()
        var form = document.getElementById('bocking')
        if(form.checkValidity()){
            $.ajax({
                type:'post',
                url:'../../model/bockings/category.php',
                data:{id:$('#services').val()}
            }).done(function(data){
                var tester = 0 // لختبار العنصر موجود مسبقا 0 يعني غير موجود
                var x = $("tbody").children().length // عدد الاصناف الفرعية
                var y = $('tbody > tr').filter(function() {
                    return $(this).css('display') === 'none';
                }).length // عدد العناصر المخفية
                var result = parseInt(x - y) // عدد العناصر الظاهرة
                tester = result == 0 ? 2 : 0 // في المرة الأولة لايتم إختبار
                if(tester != 2){
                    $('td[name="id_numbers[]"]').map(function(){
                        if($(this).text() == data.count) tester = 1
                    })
                }
                else{
                    $('table').show()
                    $('#bockingUpdate').show()
                }
                if(tester != 1){
                    $('tbody > tr').eq(result).show();
                    var quantity     = parseInt($('#quantity').val())
                    var priceUnit    = parseInt($('#type').val()) != 1 ? parseInt(data.companion_price) : parseInt(data.customer_price)
                    var totalUnits   = parseInt(quantity) * parseInt(priceUnit)
                    var priceWorker  = parseInt(data.employee_price)
                    var priceWorkers = parseInt(quantity) * priceWorker
                    var numDays      = $('#num_days').val()
                    if(numDays == "" || numDays == null){
                        numDays = 0
                    }
                    var totalUnitsDays   = totalUnits * parseInt(numDays)
                    var totalBaggages    = totalUnitsDays + priceWorkers
                    $('#id_numbers'      + result).text(data.count)
                    $('#item'            + result).text(data.name)
                    $('#quantities'      + result).val(quantity)
                    $('#worker'          + result).val(data.worker_id)
                    $('#store'           + result).val(data.store_id)
                    $('#quantityHide'    + result).val(quantity)
                    $('#price_unit'      + result).text(priceUnit)
                    $('#total_units'     + result).text(totalUnits)
                    $("#baggage").text(parseInt($("#baggage").text()) + parseInt(totalUnitsDays))
                    if($('#type').val() != 3){
                        $('#price_worker'    + result).text(priceWorker)
                        $('#price_workers'   + result).text(priceWorkers)
                        $("#workers").text(parseInt($("#workers").text()) + priceWorkers)
                        $("#remaining").val(parseInt($("#remaining").val()) + totalBaggages)
                        $("#total").text(parseInt($("#total").text()) + totalBaggages)
                    }
                    else{
                        $("#remaining").val(parseInt($("#remaining").val()) + totalUnitsDays)
                        $("#total").text(parseInt($("#total").text()) + totalUnitsDays)

                    }
                    $("#baggageHide").val(parseInt($("#baggageHide").val()) + parseInt(totalUnits))
                }
                else{
                    alert('هذا الصنف موجود مسبقا')
                }
                $("#quantity").val("")
                $("#services").val("")
            })
        }
    })
    $("#services").on('change',function(){
        var next = $("input").eq(1);
        next.focus()
    })
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
            $('#bockingUpdate').hide()
        }
    })
    $('input[name="quantities[]"]').on('keyup' , function(){
        var index        = $('input[name="quantities[]"]').index(this)
        var quantityHide = $('#quantityHide' + index).val()
        var quantity     = $('#quantities'      + index).val()
        if(quantity == "" || quantity == null || parseInt(quantity) == 0){
            quantity  = 1
            $('#quantities'      + index).val(1)
        }
        
        var priceUnit       = $('#price_unit'    + index).text()
        var priceWorker     = $('#price_worker'  + index).text()
        var priceWorkersOld = $('#price_workers' + index).text()
        var totalUnitsOld   = $('#total_units'   + index).text()
        var priceWorkersNew = parseInt(quantity) * parseInt(priceWorker)
        var totalUnitsNew   = parseInt(quantity) * parseInt(priceUnit)
        var priceWorkers    = Math.abs(parseInt(priceWorkersOld) - parseInt(priceWorkersNew))
        var totalUnits      = Math.abs(parseInt(totalUnitsOld)   - parseInt(totalUnitsNew))
        $('#price_workers'   + index).text(priceWorkersNew)
        $('#total_units'     + index).text(totalUnitsNew)
        $('#quantityHide' + index).val(quantity)
        
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
        var comparison      = parseInt(quantityHide) > parseInt(quantity) ? 1 : 0
        if(comparison == 1){
            var baggageNew      =  parseInt(baggageOld)     - (parseInt(totalUnits) * parseInt(numDaysOld))
            var workersNew      =  parseInt(workersOld)     - parseInt(priceWorkers)
            var remainingNew    =  parseInt(remainingOld)   - (parseInt(totalUnits) * parseInt(numDaysOld))
            var totalNew        =  parseInt(totalOld)       - (parseInt(totalUnits) * parseInt(numDaysOld))
            var baggageHideNew  =  parseInt(baggageHideOld) - parseInt(totalUnits)
            totalNew            -= parseInt(priceWorkers)
            if(quantity != quantityHide){
                remainingNew += parseInt(discountOld) - parseInt(priceWorkers)
            }
            else{
                remainingNew -=parseInt(priceWorkers)
            }
            $('#baggage').text(baggageNew)
            $('#workers').text(workersNew)
            $('#remaining').val(remainingNew)
            $('#total').text(totalNew)
            $('#baggageHide').val(baggageHideNew)
        }
        else{
            var baggageNew      =  parseInt(baggageOld)     + (parseInt(totalUnits) * parseInt(numDaysOld))
            var workersNew      =  parseInt(workersOld)     + parseInt(priceWorkers)
            var remainingNew    =  parseInt(remainingOld)   + (parseInt(totalUnits) * parseInt(numDaysOld))
            var totalNew        =  parseInt(totalOld)       + (parseInt(totalUnits) * parseInt(numDaysOld))
            var baggageHideNew  =  parseInt(baggageHideOld) + parseInt(totalUnits)
            totalNew            += parseInt(priceWorkers)
            if(quantity != quantityHide){
                remainingNew += parseInt(discountOld) + parseInt(priceWorkers)
            }
            else{
                remainingNew +=parseInt(priceWorkers)
            }
            $('#baggage').text(baggageNew)
            $('#workers').text(workersNew)
            $('#remaining').val(remainingNew)
            $('#total').text(totalNew)
            $('#baggageHide').val(baggageHideNew)
        }
        if(quantity != quantityHide){
            discountOld = 0
            $('#percentage_discount').val(0)
        }
        $('#discount').val(discountOld)
    })
    $('#relay').on('keyup' , function(){
        var baggage , workers , count , discount , percentage
        baggage  = $("#baggage").text()
        workers  = $("#workers").text()
        count    = parseInt(baggage) + parseInt(workers)
        discount = parseInt(baggage) + parseInt(workers)
        if($('#relay').val() != "" && $('#relay').val() != null){
            count    += parseInt($('#relay').val())
            discount += parseInt($('#relay').val())
        }
        if($('#discount').val() != "" && $('#discount').val() != null){
            percentage = (parseInt($('#discount').val())*100)/(parseInt(count))
            percentage = percentage.toFixed(0)
            discount   -= parseInt($('#discount').val())
            $("#percentage_discount").val(percentage)
        }
        $("#remaining").val(discount)
        $("#total").text(count)
    })
    $('#discount').on('keyup' , function(){
        var baggage , workers , count , discount , percentage
        baggage  = $("#baggage").text()
        workers  = $("#workers").text()
        count    = parseInt(baggage) + parseInt(workers)
        discount = parseInt(baggage) + parseInt(workers)
        if($('#relay').val() != "" && $('#relay').val() != null){
            count    += parseInt($('#relay').val())
            discount += parseInt($('#relay').val())
        }
        if($('#discount').val() != "" && $('#discount').val() != null){
            percentage = (parseInt($('#discount').val())*100)/(parseInt(count))
            percentage = percentage.toFixed(0)
            discount   -= parseInt($('#discount').val())
            $("#percentage_discount").val(percentage)
        }
        else{
            $("#percentage_discount").val(0)
            $("#discount").val(0)
        }
        $("#remaining").val(discount)
        $("#total").text(count)
    })
    $('#percentage_discount').on('keyup' , function(){
        var baggage , workers , count , discount , percentage
        baggage  = $("#baggage").text()
        workers  = $("#workers").text()
        count    = parseInt(baggage) + parseInt(workers)
        discount = parseInt(baggage) + parseInt(workers)
        if($('#relay').val() != "" && $('#relay').val() != null){
            count    += parseInt($('#relay').val())
            discount += parseInt($('#relay').val())
        }
        if($('#percentage_discount').val() != "" && $('#percentage_discount').val() != null){
            percentage = (parseInt($('#percentage_discount').val())* parseInt(count))/(100)
            percentage = percentage.toFixed(0)
            discount   -=  percentage
            $("#discount").val(percentage)
        }
        else{
            $("#percentage_discount").val(0)
            $("#discount").val(0)
        }
        $("#remaining").val(discount)
        $("#total").text(count)
    })
    $('#num_days').on('keyup' , function(){
        var baggage_hide , workers , count , discount , percentage
        baggage_hide = $("#baggageHide").val()
        workers      = $("#workers").text()
        if($('#num_days').val() != "" && $('#num_days').val() != null){
            baggage_hide *= parseInt($('#num_days').val())
        }
        else{
            $('#num_days').val(0)
        }
        count    = parseInt(baggage_hide) + parseInt(workers)
        discount = parseInt(baggage_hide) + parseInt(workers)
        if($('#relay').val() != "" && $('#relay').val() != null){
            count    += parseInt($('#relay').val())
            discount += parseInt($('#relay').val())
        }
        if($('#discount').val() != "" && $('#discount').val() != null){
            percentage = (parseInt($('#discount').val())*100)/(parseInt(count))
            percentage = percentage.toFixed(0)
            discount   -= parseInt($('#discount').val())
            $("#percentage_discount").val(percentage)
        }
        else{
            $("#percentage_discount").val(0)
            $("#discount").val(0)
        }
        $("#baggage").text(baggage_hide)
        $("#remaining").val(discount)
        $("#total").text(count)
    })
    $('#bockingUpdate').submit(function(data){
        data.preventDefault()
        var form = document.getElementById('bockingUpdate')
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
            var url = ''
            if(parseInt($('#status').val()) == 1){
                url = '../../model/bockingUpdates/bockingFirstUpdate.php'
            }
            if(parseInt($('#status').val()) == 2){
                url = '../../model/bockingUpdates/bockingFinalUpdate.php'
            }
            if(parseInt($('#status').val()) == 3){
                url = '../../model/bockingUpdates/bockingLateUpdate.php'
            }
            $.ajax({
                type:'post',
                url:url,
                data:{
                    billId      : $('#billId').val(),
                    numDays     : $('#num_days').val(),
                    baggage     : $('#baggage').text(),
                    total_price : $('#total').text(),
                    discount    : $('#discount').val(),
                    relay       : $('#relay').val(),
                    empPrice    : $('#workers').text(),
                    price       : $('#remaining').val(),
                    details     : objectA
                }
            }).done(function(){
                // window.location.reload()
            })
        }
    })
})