$(function () {
    'use strict'
    $('.panel-body').fadeToggle(100)
    $("body").on('click' , '.toggle-info' , function () {
        var index = $('.toggle-info').index(this)
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
        data.preventDefault()
        var form = document.getElementById('cashing')
        if(form.checkValidity()){
            $.ajax({
                type:'post',
                url:'../../model/stores/cashing.php',
                data:{ billId : $('#change').val()}
            }).done(function(data){
                $.each(JSON.parse(data.details) , function(){
                    var qu = this.quantity
                    var name = this.serviceName
                    $.ajax({
                        type:'post',
                        url:'../../model/stores/cashingSearch.php',
                        data:{serviceId:this.serviceId}
                    }).done(function(serviceData){
                        html += `<div class="form-label col-12 mt-1">
                        <input type="text" disabled class="form-label"
                        style="text-align:center"value='0/${qu}'/>
                        <i class="toggle-info form-label fa fa-plus fa-lg change"></i>
                        <ul class="mt-1 panel-body form-label">`
                        if(serviceData.length != 0){
                            $.each(serviceData , function(index){
                                console.log(index)
                                html += `<li class="mt-2" style="list-style-type:none">
                                ${serviceData[index].name}
                                <input type="number" class="form-label" value=0>
                                <span class="btn btn-info ml-1 exchange">صرف</span>
                                <span class="btn btn-danger ml-1">الغاء</span>
                                </li>`
                            })
                        }
                        else{
                            html += `<li class="mt-2" style="list-style-type:none">
                            ${name}
                            <input type="number" class="form-label" value=0>
                            <span class="btn btn-info ml-1 exchange">صرف</span>
                            <span class="btn btn-danger ml-1">الغاء</span>
                            </li>`
                        }
                        html += `</ul></div>`
                        $('#center').html(html)
                        $('.panel-body').fadeToggle(100)
                    })
                })
            })
            $('.sitting').append(
                `<center><button type="submit" class="btn btn-primary mt-2">حفظ</button></center>`
            )
        }
    })
    $("body").on('click' , '.exchange' , function () {
        var data = $(this).parent().parent().siblings('input').val()
        var arr = data.split('/')
        alert(arr[1])
        
    })
})