$(function () {
    'use strict'
    onload = function(){
        $('table').hide()
        $('form').eq(1).hide()
    }
    $('#loan').on('change' , function(){
        var Lending   = $(this).find('option:selected').attr('Lending')
        var repayment = $(this).find('option:selected').attr('repayment')
        var amount    = parseInt(Lending) - parseInt(repayment)
        $("#amount").attr({"max" : amount})
    })
    $('#loans').submit(function(data){
        data.preventDefault()
        var form = document.getElementById('loans')
        if(form.checkValidity()){
            if ($('input[name="loanId[]"]').length == 0){
                $('table').show()
                $('form').eq(1).show()
                $(
                    `<div class="col-12">
                        <input type="hidden" class="form-control"
                        name="loanId[]"value="${$('#loan').val()}">
                    </div>`
                ).insertBefore("#save")
                $('tbody').append(
                    `<tr> 
                        <td name="itemName[]">${$("#loan").find('option:selected').text()}</td>
                        <td name="amount[]">${$("#amount").val()}</td>
                        <td name="delete[]"><i class='fa fa-remove delete'></td>
                    </tr>`
                )
            }
            else{
                var tester = 0
                $('input[name="loanId[]"]').map(function(){
                    if(this.value == $('#loan').val()) tester = 1
                })
                if(tester != 1){
                    $(
                        `<div class="col-12">
                            <input type="hidden" class="form-control"
                            name="loanId[]"value="${$('#loan').val()}">
                        </div>`
                    ).insertBefore("#save")
                    $('tbody').append(
                        `<tr> 
                            <td name="itemName[]">${$("#loan").find('option:selected').text()}</td>
                            <td name="amount[]">${$("#amount").val()}</td>
                            <td name="delete[]"><i class='fa fa-remove delete'></td>
                        </tr>`
                    )
                }
                else{
                    alert('هذا الصنف موجود مسبقا')
                }
            }
            $("#amount").val("")
            $("#loan").val("")
        }
    })
    $("body").on("click", 'td[name="delete[]"]', function(){
        var x = $("tbody").children().length
        var index = $('td[name="delete[]"]').index(this)
        $('tbody > tr').eq(index).remove()
        $('#dataTable > div').eq(index).remove()
        if(x == 1){
            $('table').hide()
            $('form').eq(1).hide()
        }
        $("#amount").val("")
        $("#loan").val("")
    })
    $("body").on('submit' , '#dataTable' , function(data){
        data.preventDefault()
        var loanId = $('input[name="loanId[]"]').map(function(){
            return $(this).val()
        })
        var amount = $('td[name="amount[]"]').map(function(){
            return $(this).text()
        })
        var objectA = []
        for (let index = 0; index < loanId.length; index++) {
            var feed
            feed = {
                "loanId" : loanId[index],
                "amount" : amount[index]
            }
            objectA.push(feed)
        }
        $.ajax({
            type:'post',
            url:'../../model/settings/managementLoan/repaymentInsert.php',
            data:{details : objectA}
        }).done(function(result){
            if(result != true){
                alert('لم يتم التعديل')
                window.location.reload()
            }
            else{
                alert('تم التعديل بنجاح')
                window.location.reload()
            }
        })
    })
})