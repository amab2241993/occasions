$(function () {
    'use strict'
    onload = function(){
        $('table').hide()
        $('#bockings').hide()
        $.ajax({
            type:'post',
            url:'../../model/dashboard/bockings.php',
            data:{customerId:$('#type').val()}
        }).done(function(data){
            $('#customer').find('option').remove().end()
            $('#customer').append(`<option disabled selected value="">${'إختار العميل'}</option>`)
            $.each(data, function(index){
                $('#customer').append(`<option value="${data[index].id}">${data[index].name}</option>`)
            })
        })
    }
    $('#type').on('change',function(){
        $.ajax({
            type:'post',
            url:'../../model/dashboard/bockings.php',
            data:{customerId:$('#type').val()}
        }).done(function(data){
            $('#customer').find('option').remove().end()
            $('#customer').append(`<option disabled selected value="">${'إختار العميل'}</option>`)
            $.each(data, function(index){
                $('#customer').append(`<option value="${data[index].id}">${data[index].name}</option>`)
            })
        })
    })
    $('#customers').submit(function(data){
        data.preventDefault()
        var form = document.getElementById('customers')
        if(form.checkValidity()){
            var status = $('#type').val() != 1 ? 2 : 1
            $.ajax({
                type:'post',
                url:'../../model/dashboard/customers.php',
                data:{
                    name    : $('#customerName').val(),
                    phone   : $('#phone').val(),
                    address : $('#address').val(),
                    status  : status,
                },
                cache:false
            }).done(function(){
                window.location = "bocking.php?date="+$("#date").val()
            })
        }
    })
    $("#services").on('change',function(){
        var next = $("input").eq(2)
        next.focus()
    })
    $('#bocking').submit(function(data){
        data.preventDefault()
        var form = document.getElementById('bocking')
        if(form.checkValidity()){
            $.ajax({
                type:'post',
                url:'../../model/dashboard/category.php',
                data:{id:$('#services').val()}
            }).done(function(data){
                var tester = 0 // لختبار العنصر موجود مسبقا 0 يعني غير موجود
                var result = parseInt($('td[name="id_numbers[]"]').length)
                tester = result == 0 ? 2 : 0 // في المرة الأولة لايتم إختبار
                if(tester != 2){
                    $('td[name="id_numbers[]"]').map(function(){
                        if($(this).text() == data.count) tester = 1
                    })
                }
                else{
                    $('table').show()
                    $('#bockings').show()
                    if(!$("#type").is(':disabled')){
                        $("#type").attr({"disabled" : true})
                    }
                    if(!$("#customer").is(':disabled')){
                        $("#customer").attr({"disabled" : true})
                    }
                }
                if(tester != 1){
                    var quantity     = parseInt($('#quantity').val())
                    var priceUnit    = parseInt($('#type').val()) != 1 ? parseInt(data.companion_price) : parseInt(data.customer_price)
                    var totalUnits   = parseInt(quantity) * parseInt(priceUnit)
                    var priceWorker  = parseInt(data.employee_price)
                    var priceWorkers = parseInt(quantity) * priceWorker
                    var numDays      = $('#num_days').val()
                    if(numDays == "" || numDays == null){
                        numDays = 1
                    }
                    var totalUnitsDays   = totalUnits * parseInt(numDays)
                    var totalBaggages    = totalUnitsDays + priceWorkers
                    var html = `<tr>
                    <td class="col-1 mb-2" name="id_numbers[]" id="id_numbers${result}">${data.count}</td>
                    <td class="col-1 mb-2" name="item[]" id="item${result}">${data.name}</td>
                    <td class="col-1 mb-2" style="width:15%">
                    <input class='form-control'
                    type='number' name='quantities[]' id="quantities${result}" value=${quantity}>
                    </td>
                    <td class="col-2 mb-2" name="price_unit[]"    id="price_unit${result}">${priceUnit}</td>`
                    var form =  `<input type="hidden" name="worker[]" id="worker${result}" value=${data.worker_id}>`
                        form += `<input type="hidden" name="store[]" id="store${result}" value=${data.store_id}>`
                        form += `<input type="hidden" id="quantityHide${result}" value=${quantity}>`
                    $("#baggage").text(parseInt($("#baggage").text()) + parseInt(totalUnitsDays))
                    $("#baggageHide").val(parseInt($("#baggageHide").val()) + parseInt(totalUnits))
                    if($('#type').val() != 3){
                        $("#workers").text(parseInt($("#workers").text()) + priceWorkers)
                        $("#remaining").val(parseInt($("#remaining").val()) + totalBaggages)
                        $("#total").text(parseInt($("#total").text()) + totalBaggages)
                    }
                    else{
                        priceWorker  = 0
                        priceWorkers = 0
                        $("#remaining").val(parseInt($("#remaining").val()) + totalUnitsDays)
                        $("#total").text(parseInt($("#total").text()) + totalUnitsDays)
                    }
                    html += `<td class="col-2 mb-2" name="price_worker[]"  id="price_worker${result}">${priceWorker}</td>
                    <td class="col-2 mb-2" name="price_workers[]" id="price_workers${result}">${priceWorkers}</td>
                    <td class="col-2 mb-2" name="total_units[]"   id="total_units${result}">${totalUnits}</td>
                    <td class="col-1 mb-2">
                    <i class='fa fa-remove delete'></i>
                    </td></tr>`
                    $('tbody').append(html)
                    $('#bockings').append(form)
                }
                else{
                    alert('هذا الصنف موجود مسبقا')
                }
                $("#quantity").val("")
                $("#services").val("")
            })
        }
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
    $('body').on('keyup' , 'input[name="quantities[]"]' , function(){
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
            numDaysOld  = 1
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
    $('body').on('click' , '.delete' , function(){
        var index        = $('.delete').index(this)
        var priceWorkers = $('#price_workers'   + index).text()
        var totalUnits   = $('#total_units'     + index).text()
        var baggageOld   = $('#baggage').text()
        var workersOld   = $('#workers').text()
        var numDaysOld   = $('#num_days').val()
        var relay        = $('#relay').val()
        if(relay == "" || relay == null ){
            relay  = 0
        }
        if(numDaysOld == "" || numDaysOld == null ){
            numDaysOld  = 1
        }
        var baggage     =  parseInt(baggageOld) - (parseInt(totalUnits) * parseInt(numDaysOld))
        var baggageHide =  parseInt($('#baggageHide').val()) - parseInt(totalUnits)
        var workers     =  parseInt(workersOld) - parseInt(priceWorkers)
        var remaining   =  parseInt(baggage) + parseInt(relay) + parseInt(workers)
        var total       =  parseInt(baggage) + parseInt(relay) + parseInt(workers)

        $('#baggage').text(baggage)
        $('#baggageHide').val(baggageHide)
        $('#workers').text(workers)
        $('#remaining').val(remaining)
        $('#total').text(total)
        $('#percentage_discount').val(0)
        $('#discount').val(0)
        $(this).parent().parent().remove()
        $('#quantityHide' + index).remove()
        $('#store' + index).remove()
        $('#worker' + index).remove()
        var x = $("tbody").children().length
        for (var counter = index; counter < x; counter++) {
            console.log(counter)
            $('#id_numbers'    + (counter + 1)).attr("id",("id_numbers"    + counter))
            $('#item'          + (counter + 1)).attr("id",("item"          + counter))
            $('#quantities'    + (counter + 1)).attr("id",("quantities"    + counter))
            $('#quantityHide'  + (counter + 1)).attr("id",("quantityHide"  + counter))
            $('#price_unit'    + (counter + 1)).attr("id",("price_unit"    + counter))
            $('#price_worker'  + (counter + 1)).attr("id",("price_worker"  + counter))
            $('#price_workers' + (counter + 1)).attr("id",("price_workers" + counter))
            $('#total_units'   + (counter + 1)).attr("id",("total_units"   + counter))
            $('#worker'        + (counter + 1)).attr("id",("worker"        + counter))
            $('#store'         + (counter + 1)).attr("id",("store"         + counter))
        }
        if($('input[name="quantities[]"]').length == 0){
            $('#bockings').hide()
            $('table').hide()
            if($("#type").is(':disabled')){
                $("#type").attr({"disabled" : false})
            }
            if($("#customer").is(':disabled')){
                $("#customer").attr({"disabled" : false})
            }
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
                var feed
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
                url:'../../model/dashboard/bocking.php',
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
                            status : 1
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
                                    window.location = "../bockings/bockingFirst.php"
                                }
                            })
                        }
                    })
                }
            })
        }
    })
})