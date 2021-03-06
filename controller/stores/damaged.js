$(function () {
    'use strict'
    onload = function(){
        $('table').hide()
        $('form').eq(1).hide()
    }
    $('#stores').on('change' , function(){
        $.ajax({
            type:'post',
            url:'../../model/stores/damaged.php',
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
    $('#item').on('change',function(){
        var quantity = $(this).find('option:selected').attr('quantity')
        if($("#quantity").is(':disabled')){
            $("#quantity").attr({"disabled" : false})
        }
        $("#quantity").attr({"max" : quantity})
    })
    
    $('#damaged').submit(function(data){
        data.preventDefault()
        var form = document.getElementById('damaged')
        if(form.checkValidity()){
            if ($('input[name="storeId[]"]').length == 0){
                $('table').show()
                $('form').eq(1).show()
                $(
                    `<div class="col-3">
                        <input type="hidden" class="form-control"
                        name="parentId[]" value="${$("#item").find('option:selected').attr('parentId')}">
                    </div>`
                ).insertBefore("#save")
                $(
                    `<div class="col-3">
                        <input type="hidden" class="form-control"
                        name="storeId[]"value="${$('#stores').val()}">
                    </div>`
                ).insertBefore("#save")
                $(
                    `<div class="col-3">
                        <input type="hidden" class="form-control"
                        name="itemId[]"value="${$('#item').val()}">
                    </div>`
                ).insertBefore("#save")
                $(
                    `<div class="col-3">
                        <input type="hidden" class="form-control"
                        name="workerId[]"value="${$('#item').find('option:selected').attr('worker')}">
                    </div>`
                ).insertBefore("#save")
                $('tbody').append(
                    `<tr> 
                        <td name="itemName[]">${$("#item").find('option:selected').text()}</td>
                        <td name="quantity[]">${$("#quantity").val()}</td>
                        <td>${$("#item").find('option:selected').attr('quantity')}</td>
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
                        `<div class="col-3">
                            <input type="hidden" class="form-control"
                            name="parentId[]" value="${$("#item").find('option:selected').attr('parentId')}">
                        </div>`
                    ).insertBefore("#save")
                    $(
                        `<div class="col-3">
                            <input type="hidden" class="form-control"
                            name="storeId[]"value="${$('#stores').val()}">
                        </div>`
                    ).insertBefore("#save")
                    $(
                        `<div class="col-3">
                            <input type="hidden" class="form-control"
                            name="itemId[]"value="${$('#item').val()}">
                        </div>`
                    ).insertBefore("#save")
                    $(
                        `<div class="col-3">
                            <input type="hidden" class="form-control"
                            name="workerId[]"value="${$('#item').find('option:selected').attr('worker')}">
                        </div>`
                    ).insertBefore("#save")
                    $('tbody').append(
                        `<tr> 
                           <td name="itemName[]">${$("#item").find('option:selected').text()}</td>
                           <td name="quantity[]">${$("#quantity").val()}</td>
                           <td>${$("#item").find('option:selected').attr('quantity')}</td>
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
        $('#dataTable > div').eq(index * 4 ).remove()
        $('#dataTable > div').eq(index * 4 ).remove()
        $('#dataTable > div').eq(index * 4 ).remove()
        $('#dataTable > div').eq(index * 4 ).remove()
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
        var workerId = $('input[name="workerId[]"]').map(function(){
            return $(this).val()
        })
        var quantity = $('td[name="quantity[]"]').map(function(){
            return $(this).text()
        })
        var objectA = []
        for (let index = 0; index < storeId.length; index++) {
            var feed
            feed = {
                "itemId"   : itemId[index],
                "storeId"  : storeId[index],
                "parentId" : parentId[index],
                "workerId" : workerId[index],
                "quantity" : quantity[index]
            }
            objectA.push(feed)
        }
        console.log(objectA)
        $.ajax({
            type:'post',
            url:'../../model/stores/damagedInsert.php',
            data:{details : objectA}
        }).done(function(){
            alert("تم التحويل بنجاح")
            window.location.reload()
        })
    })
})