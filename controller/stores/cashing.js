$(function () {
    'use strict'
    $('#lastSave').hide()
    $('.panel-body').fadeToggle(100)
    $("body").on('click' , '.toggle-info' , function () {
        // var index = $('.toggle-info').index(this)
        $(this).next('.panel-body').fadeToggle(100)
        if ($(this).hasClass('fa-plus')) {
            $(this).addClass("fa-minus")
            $(this).removeClass("fa-plus")
        }
        else {
            $(this).addClass("fa-plus")
            $(this).removeClass("fa-minus")
        }
    })
    $('.js-example-basic-single').select2()   
    $('#cashing').on('submit' , function(data){
        var html = ''
        var forms = ''
        data.preventDefault()
        var form = document.getElementById('cashing')
        if(form.checkValidity()){
            $.ajax({
                type:'post',
                url:'../../model/stores/cashing.php',
                data:{ billId : $('#change').val()}
            }).done(function(data){
                $.each(JSON.parse(data.details) , function(counter){
                    var qu  = this.quantity
                    var bId = $('#change').val()
                    $.ajax({
                        type:'post',
                        url:'../../model/stores/cashingSearch.php',
                        data:{serviceId:this.serviceId}
                    }).done(function(serviceData){
                        html += `<div class="form-label col-12 mt-1">
                        <input type="text" disabled class="form-label" style="text-align:center"
                        value='0/${qu}' quantity="${qu}" />
                        <i class="toggle-info form-label fa fa-plus fa-lg change"></i>
                        <ul class="mt-1 panel-body form-label" quantity="${qu}" count ="${counter}">`
                        $.each(serviceData , function(index){
                            html += `<li class="mt-2" quantity="${serviceData[index].quantity}"
                            style="list-style-type:none">
                            ${serviceData[index].name}
                            <input type="number" class="form-label check${counter}"
                            name="check[]" value="0">
                            <span class="btn btn-info ml-1 exchange${counter}" name="span[]">صرف</span>
                            </li>`
                            forms += `<input type="hidden" name="all[]" class="col-12"
                            ssId="${serviceData[index].ssId}" pId="${serviceData[index].parent_id}" billId="${bId}" quIn="0" quOut="0" id="${counter}${index}" serviceId="${serviceData[index].id}" storeId="${serviceData[index].store_id}"/>`
                        })
                        html += `</ul></div>`
                        $('#center').html(html)
                        $('#all').html(forms)
                        $('.panel-body').fadeToggle(100)
                    })
                })
            })
            $('#save').html(`<button type="submit" class="btn btn-primary mt-2">حفظ</button>`)
        }
    })
    $("body").on('keyup' , 'input[name="check[]"]' , function(){
        var index = $(this).parent().parent().attr('count')
        var check = 0
        $(".check" + index).map(function(){
            check += parseInt(this.value)
        })
        if(check > parseInt($(this).parent().parent().attr('quantity'))){
            alert("هذه الكمية اكبر من المحجوزة")
            $(this).val(0)
        }
    })
    $("body").on('click' , 'span[name="span[]"]' , function () {
        var index    = $(this).parent().parent().attr('count')
        var parentId = $(this).parent().index()
        var quantity = $(this).parent().attr('quantity')
        var value    = $(this).siblings('input').val()
        var quIn     = $('#'+index+''+parentId).attr('quIn')
        var quOut    = $('#'+index+''+parentId).attr('quOut')
        if((parseInt(quIn) + parseInt(quOut)) != parseInt(value)){
            if(!$("#saveForm").is(":visible")){
                $('#saveForm').show()
            }
            if($("#lastSave").is(":visible")){
                $('#lastSave').hide()
            }
            $('tbody').empty()
            if($('body').find('#sub'+index+''+parentId).length > 0){
                $('body').find('#sub'+index+''+parentId).remove()
            }
            if($('body').find('.dd').length > 0){
                $('body').find('.dd').remove()
            }
            if(parseInt(value) > parseInt(quantity)){
                var xxx = parseInt(value) - parseInt(quantity)
                $('#'+index+''+parentId).attr('quIn'  , quantity)
                if(confirm("عفوا الكمية غير كافية!\nهل تريد استلاف " + xxx + "قطعة من مكتب اخر")){
                    $('#ids').val(index + '' + parentId)
                    $('#qus').val(xxx)
                    $('#piece').attr('max' , xxx)
                    $('#out').modal('show')
                    $(".invalids").text('عدد القطع يجب ان يكون اكبر من 0 واقل من ' + (parseInt(xxx) + 1))
                }
            }
            else{
                $('#'+index+''+parentId).attr('quOut' , 0)
                $('#'+index+''+parentId).attr('quIn' , value)
                alert("تم الصرف بنجاح")
            }
        }
        // var data = $(this).parent().parent().siblings('input').val()
        // var arr = data.split('/')
    })
    $('#saveForm').submit(function(data){
        data.preventDefault()
        var form = document.getElementById('saveForm')
        if(form.checkValidity()){
            if ($('input[name="customerId[]"]').length == 0){
                $('#lastSave').show()
                $(
                    `<div class="col-12 dd">
                        <input type="hidden" class="form-control"
                        name="customerId[]" value="${$('#name').val()}">
                    </div>`
                ).insertBefore("#lastSave")
                $('tbody').append(
                    `<tr> 
                        <td name="name[]">${$("#name").find('option:selected').text()}</td>
                        <td name="piece[]">${$("#piece").val()}</td>
                        <td name="delete[]"><i class='fa fa-remove delete'></td>
                    </tr>`
                )
            }
            else{
                var tester = 0
                $('input[name="customerId[]"]').map(function(){
                    if(this.value == $('#name').val()) tester = 1
                })
                if(tester != 1){
                    $(
                        `<div class="col-12 dd">
                            <input type="hidden" class="form-control"
                            name="customerId[]" value="${$('#name').val()}">
                        </div>`
                    ).insertBefore("#lastSave")
                    $('tbody').append(
                        `<tr> 
                            <td name="name[]">${$("#name").find('option:selected').text()}</td>
                            <td name="piece[]">${$("#piece").val()}</td>
                            <td name="delete[]"><i class='fa fa-remove delete'></td>
                        </tr>`
                    )
                }
                else{
                    alert('هذا الصنف موجود مسبقا')
                }
            }
            var counter = 0
            $('body').find('td[name="piece[]"]').map(function(){
                counter += parseInt($(this).text())
            })
            if(parseInt(counter) == parseInt($('#qus').val())){
                $('#saveForm').hide()
            }
            else{
                var max = parseInt($('#qus').val() - parseInt(counter))
                $('#piece').attr('max' , max)
                $(".invalids").text('عدد القطع يجب ان يكون اكبر من 0 واقل من ' + (parseInt(max) + 1))
            }
            $("#piece").val("")
            $("#name").val("")
        }
    })
    $("body").on("click", 'td[name="delete[]"]', function(){
        var x = $("tbody").children().length
        var index = $('td[name="delete[]"]').index(this)
        $('tbody > tr').eq(index).remove()
        $('#dataTable > div').eq(index).remove()
        if(x == 1){
            $('#piece').attr('max' , $('#qus').val())
            $(".invalids").text('عدد القطع يجب ان يكون اكبر من 0 واقل من ' + (parseInt($('#qus').val()) + 1))
            $('#lastSave').hide()
        }
        else{
            var counter = 0
            $('body').find('td[name="piece[]"]').map(function(){
                counter += parseInt($(this).text())
            })
            var max = parseInt($('#qus').val()) - parseInt(counter)
            $('#piece').attr('max' , max)
            $(".invalids").text('عدد القطع يجب ان يكون اكبر من 0 واقل من ' + (parseInt(max) + 1))
        }
        if(!$("#saveForm").is(":visible")){
            $('#saveForm').show()
        }
        $("#piece").val("")
        $("#name").val("")
    })
    $("body").on('submit' , '#dataTable' , function(data){
        data.preventDefault()
        var htmls = `<div id=sub${$('#ids').val()}>`
        $('body').find('td[name="piece[]"]').map(function(){
            htmls += `<input type="hidden" name="pieces[]" value="${$(this).text()}" />`
        })
        $('body').find('input[name="customerId[]"]').map(function(){
            htmls += `<input type="hidden" name="cId[]" value="${$(this).val()}" />`
        })
        htmls += `</div>`
        $('#lastForm').append(htmls)
        if(!$("#saveForm").is(":visible")){
            $('#saveForm').show()
        }
        if($("#lastSave").is(":visible")){
            $('#lastSave').hide()
        }
        $('#' + $('#ids').val()).attr('quOut' , $('#qus').val())
        $('tbody').empty()
        $('.dd').remove()
        $('#out').modal('hide')
    })
    $('#ssss').on('submit',function(data){
        data.preventDefault()
        var objectA = []
        $('body').find($('input[name="all[]"]')).map(function(){
            var quIn = $(this).attr('quIn')
            var quOut = $(this).attr('quOut')
            
            if(parseInt(quIn) != 0 || parseInt(quOut) != 0){
                var  quOuts = []
                var oprtor = $(this).attr('id')
                if($('#sub'+oprtor).length > 0){
                    var pieces = $('body').find($('#sub'+oprtor).find($('input[name="pieces[]"]')))
                    .map(function(){
                        return $(this).val()
                    })
                    var cId = $('body').find($('#sub'+oprtor).find($('input[name="cId[]"]')))
                    .map(function(){
                        return $(this).val()
                    })
                    for (let index = 0; index < cId.length; index++) {
                        var out
                        out = {
                            "pieces" : pieces[index],
                            "cId"    : cId[index],
                        }
                        quOuts.push(out)
                    }
                }
                else{
                    var out
                    out = {
                        "pieces" : 0,
                        "cId"    : 0,
                    }
                    quOuts.push(out)
                }
                var feed
                feed = {
                    "ssId"      : $(this).attr('ssId'),
                    "serviceId" : $(this).attr('serviceId'),
                    "storeId"   : $(this).attr('storeId'),
                    "parentId"  : $(this).attr('pId'),
                    "billId"    : $(this).attr('billId'),
                    "quIn"      : $(this).attr('quIn'),
                    "quOut"     : quOuts,
                }
                objectA.push(feed)
            }
        })
        if(objectA.length > 0){
            
            $.ajax({
                type:'post',
                url:'../../model/stores/insertCashing.php',
                data:{details:objectA}
            }).done(function(){
            })
        }
    })
})