$(function () {
    'use strict'
    onload = function(){
        $('table').hide()
        $('form').eq(1).hide()
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
            if($("#item").is(':disabled')){
                $("#item").attr({"disabled" : false})
            }
            $('#item').find('option').remove().end()
            $('#item').append(`<option disabled selected value="">${'إختار الصنف'}</option>`)
            $.each(data, function(index){
                $('#item').append(
                    `<option value=${data[index].id} parentId=${data[index].parent_id}
                     worker=${data[index].worker_id} quantity=${data[index].quantity}>
                     ${data[index].name}</option>`
                )
            })
        })
    })
    $('#calculation').on('change' , function(){
        var quantity = $("#item").find('option:selected').attr('quantity')
        if(!($("#quantity").is(':disabled'))){
            if($('#calculation').val() == 2){
                $("#quantity").attr({"max" : quantity})
            }
            else{
                $("#quantity").removeAttr("max")
            }
        }
    })
    $('#item').on('change',function(){
        var quantity = $(this).find('option:selected').attr('quantity')
        if($("#quantity").is(':disabled')){
            $("#quantity").attr({"disabled" : false})
        }
        if($('#calculation').val() == 2){
            $("#quantity").attr({"max" : quantity})
        }
        else{
            $("#quantity").removeAttr("max")
        }
    })
    
    $('#goods').submit(function(data){
        data.preventDefault()
        var form = document.getElementById('goods')
        if(form.checkValidity()){
            var quantity    = $("#quantity").val()
            var oldQuantity = $("#item").find('option:selected').attr('quantity');
            var newQuantity
            if($('#calculation').val() == 2){
                newQuantity = parseInt(oldQuantity) - parseInt(quantity)
            }
            else{
                newQuantity = parseInt(oldQuantity) + parseInt(quantity)
            }
            if ($('input[name="storeId[]"]').length == 0){
                $('table').show()
                $('form').eq(1).show()
                $(
                    `<div class="col-4">
                        <input type="hidden" class="form-control"
                        name="parentId[]" value="${$("#item").find('option:selected').attr('parentId')}">
                    </div>`
                ).insertBefore("#save")
                $(
                    `<div class="col-4">
                        <input type="hidden" class="form-control"
                        name="storeId[]"value="${$('#stores').val()}">
                    </div>`
                ).insertBefore("#save")
                $(
                    `<div class="col-4">
                        <input type="hidden" class="form-control"
                        name="itemId[]"value="${$('#item').val()}">
                    </div>`
                ).insertBefore("#save")
                $('tbody').append(
                    `<tr> 
                        <td name="itemName[]">${$("#item").find('option:selected').text()}</td>
                        <td name="calculation[]">${$("#calculation").find('option:selected').text()}</td>
                        <td name="quantity[]">${$("#quantity").val()}</td>
                        <td name="oldQuantity[]">${$("#item").find('option:selected').attr('quantity')}</td>
                        <td name="newQuantity[]">
                            ${newQuantity}
                        </td>
                        <td name="delete[]"><i class='fa fa-remove delete'></td>
                    </tr>`
                )
            }
            else{
                var tester = 0
                $('input[name="itemId[]"]').map(function(){
                    if(this.value == $('#item').val()) tester = 1
                })
                if(tester != 1){
                    $(
                        `<div class="col-4">
                            <input type="hidden" class="form-control"
                            name="parentId[]" value="${$("#item").find('option:selected').attr('parentId')}">
                        </div>`
                    ).insertBefore("#save")
                    $(
                        `<div class="col-4">
                            <input type="hidden" class="form-control"
                            name="storeId[]"value="${$('#stores').val()}">
                        </div>`
                    ).insertBefore("#save")
                    $(
                        `<div class="col-4">
                            <input type="hidden" class="form-control"
                            name="itemId[]"value="${$('#item').val()}">
                        </div>`
                    ).insertBefore("#save")
                    $('tbody').append(
                        `<tr> 
                            <td name="itemName[]">${$("#item").find('option:selected').text()}</td>
                            <td name="calculation[]">${$("#calculation").find('option:selected').text()}</td>
                            <td name="quantity[]">${$("#quantity").val()}</td>
                            <td name="oldQuantity[]">${$("#item").find('option:selected').attr('quantity')}</td>
                            <td name="newQuantity[]">
                                ${newQuantity}
                            </td>
                            <td name="delete[]"><i class='fa fa-remove delete'></td>
                        </tr>`
                    )
                }
                else{
                    alert('هذا الصنف موجود مسبقا')
                }
            }
            $('#item').find('option').remove().end()
            $("#quantity").val("")
            $("#stores").val("")
            $("#item").attr({"disabled" : true})
            $("#quantity").attr({"disabled" : true})
        }
    })
    $("body").on("click", 'td[name="delete[]"]', function(){
        var x = $("tbody").children().length
        var index = $('td[name="delete[]"]').index(this)
        $('tbody > tr').eq(index).remove()
        $('#dataTable > div').eq(index * 3 ).remove()
        $('#dataTable > div').eq(index * 3 ).remove()
        $('#dataTable > div').eq(index * 3 ).remove()
        if(x == 1){
            $('table').hide()
            $('form').eq(1).hide()
        }
        $('#item').find('option').remove().end()
        $("#quantity").val("")
        $("#stores").val("")
        $("#item").attr({"disabled" : true})
        $("#quantity").attr({"disabled" : true})
    })
    $('#dataTable').submit(function(data){
        data.preventDefault()
        var itemId = $('input[name="itemId[]"]').map(function(){
            return $(this).val()
        })
        var storeId = $('input[name="storeId[]"]').map(function(){
            return $(this).val()
        })
        var parentId = $('input[name="parentId[]"]').map(function(){
            return $(this).val()
        })
        var calculation = $('td[name="calculation[]"]').map(function(){
            return $(this).text()
        })
        var quantity = $('td[name="quantity[]"]').map(function(){
            return $(this).text()
        })
        var oldQuantity = $('td[name="oldQuantity[]"]').map(function(){
            return $(this).text()
        })
        var newQuantity = $('td[name="newQuantity[]"]').map(function(){
            return $(this).text()
        })
        var objectA = []
        for (let index = 0; index < storeId.length; index++) {
            var feed
            feed = {
                "itemId"      : itemId[index],
                "storeId"     : storeId[index],
                "parentId"    : parentId[index],
                "calculation" : calculation[index],
                "quantity"    : quantity[index],
                "oldQuantity" : oldQuantity[index],
                "newQuantity" : newQuantity[index]
            }
            objectA.push(feed)
        }
        console.log(objectA)
        $.ajax({
            type:'post',
            url:'../../model/stores/goodsInsert.php',
            data:{details : objectA}
        }).done(function(){
            alert("تم التحويل بنجاح")
            window.location.reload()
        })
    })
})