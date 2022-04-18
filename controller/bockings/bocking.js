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
    $('#type').on('change',function(){
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
    })
    $('#customers').submit(function(data){
        data.preventDefault()
        var form = document.getElementById('customers')
        if(form.checkValidity()){
            var status = $('#type').val() != 1 ? 2 : 1
            $.ajax({
                type:'post',
                url:'../../model/bockings/customers.php',
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
                var result = parseInt($('td[name="id_numbers[]"]').length)
                if(result == 0)
                alert(result)
                tester = result == 0 ? 2 : 0 // في المرة الأولة لايتم إختبار
                if(tester != 2){
                    $('td[name="id_numbers[]"]').map(function(){
                        if($(this).text() == data.count) tester = 1
                    })
                }
                else{
                    $('table').show()
                    $('#bockings').show()
                    $("#type").attr({"disabled" : true})
                    $("#customer").attr({"disabled" : true})
                }
                if(tester != 1){
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
                    var html = `<tr>
                    <td scope="col-1 mb-2" name="id_numbers[]" id="id_numbers${result}">${data.count}</td>
                    <td scope="col-1 mb-2" name="item[]" id="item${result}">${data.name}</td>
                    <td scope="col-1 mb-2" style="width:15%">
                    <input class='form-control'
                    type='number' name='quantities[]' id="quantities${result}" value=${quantity}>
                    </td>
                    <td scope="col-2 mb-2" name="price_unit[]"    id="price_unit${result}">${priceUnit}</td>`
                    $("#baggage").text(parseInt($("#baggage").text()) + parseInt(totalUnitsDays))
                    $("#baggageHide").val(parseInt($("#baggageHide").val()) + parseInt(totalUnits))
                    if($('#type').val() != 3){
                        $("#workers").text(parseInt($("#workers").text()) + priceWorkers)
                        $("#remaining").val(parseInt($("#remaining").val()) + totalUnitsDays)
                        $("#total").text(parseInt($("#total").text()) + totalUnitsDays)
                    }
                    else{
                        priceWorker  = 0
                        priceWorkers = 0
                        $("#remaining").val(parseInt($("#remaining").val()) + totalUnitsDays)
                        $("#total").text(parseInt($("#total").text()) + totalUnitsDays)
                    }
                    html += `<td scope="col-2 mb-2" name="price_worker[]"  id="price_worker${result}">${priceWorker}</td>
                    <td scope="col-2 mb-2" name="price_workers[]" id="price_workers${result}">${priceWorkers}</td>
                    <td scope="col-2 mb-2" name="total_units[]"   id="total_units${result}">${totalUnits}</td>
                    <td scope="col-1 mb-2" name="delete[]" id="delete${result}">
                    <i class='fa fa-remove delete'>
                    </td></tr>`
                    $('tbody').append(html)
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
})