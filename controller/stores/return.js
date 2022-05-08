$(function () {
    'use strict'
    $('body').on('keyup' , 'input[name="alls[]"]' , function () {
        if(parseInt(this.value) >= parseInt($(this).attr('old'))){
            $(this).val($(this).attr('old'))
        }
        $(this).parent().siblings('.class').attr('quantity' , this.value)
        // $('div').eq(parseInt(index) + 2).find('button').attr('quantity' , this.value)
    })
    $('button[name="check[]"]').on('click' , function () {
        var code = $(this).attr('code')
        var forms = ``
        $('input[id="row[]"]').map(function () {
            if($(this).attr('code') == code){
                forms += `<tr>
                <td class="col-2">${$(this).val()}</td>
                <td class="col-3" style="width:15%">
                    <input type="number" class="form-control" name="alls[]" old="${$(this).attr('qu')}" value="${$(this).attr('qu')}" />
                </td>
                <td class="col-2 class" old="${$(this).attr('qu')}" quantity="${$(this).attr('qu')}" pId="${$(this).attr('pId')}">
                    <button class="btn btn-primary" name="return[]" cId="${$(this).attr('cId')}" sId="${$(this).attr('sId')}" >استرجاع</button>
                </td>
                <td class="col-5 class" old="${$(this).attr('qu')}" quantity="${$(this).attr('qu')}" pId="${$(this).attr('pId')}">
                    <button class="btn btn-primary" name="damaged[]" cId="${$(this).attr('cId')}" sId="${$(this).attr('sId')}" >تحويل الي التالف</button>
                </td>
                </tr>`
            }
        })
        $('#tbody').html(forms)
        $('#out').modal('show')
    })
    $('body').on('click' , 'button[name="return[]"]' , function (){
        var cashingId = $(this).attr('cid')
        var serviceId = $(this).attr('sid')
        var parentId  = $(this).parent().attr('pId')
        var quantity  = $(this).parent().attr('quantity')
        var old       = $(this).parent().attr('old')
        $.ajax({
            type:'post',
            url:'../../model/stores/returnStore.php',
            data:{
                cashingId : cashingId ,
                serviceId : serviceId ,
                parentId  : parentId ,
                quantity  : quantity ,
                old       :  old
            }
        }).done(function(){
            alert('تم')
        })
    })
    $('body').on('click' , 'button[name="damaged[]"]' , function (){
        var cashingId = $(this).attr('cid')
        var serviceId = $(this).attr('sid')
        var parentId  = $(this).parent().attr('pId')
        var quantity  = $(this).parent().attr('quantity')
        var old       = $(this).parent().attr('old')
        $.ajax({
            type:'post',
            url:'../../model/stores/returnDamaged.php',
            data:{
                cashingId : cashingId ,
                serviceId : serviceId ,
                parentId  : parentId ,
                quantity  : quantity ,
                old       :  old
            }
        }).done(function(){
            alert('تم')
        })
    })
})