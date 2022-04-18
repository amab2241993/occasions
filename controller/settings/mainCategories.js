$(function () {
	'use strict';
    var quantity;
    $('#mainCategory').submit(function(data){
        data.preventDefault()
        var form = document.getElementById('mainCategory');
        if(form.checkValidity()){
            quantity = $('#quantity').val() == "" ? 0 :$('#quantity').val()
            $.ajax({
                type:'post',
                url:'../../model/settings/mainCategories.php',
                data:{
                    name     : $('#name').val(),
                    priceCus : $('#priceCus').val(),
                    priceCom : $('#priceCom').val(),
                    priceEmp : $('#priceEmp').val(),
                    quantity : quantity,
                    store    : $('#store').val(),
                    worker   : $('#worker').val()
                },
                success:function(){
                    window.location.reload()
                    alert("success");
                }
            })
        }
    })

    $('.update').submit(function(data){
        data.preventDefault()
        var index = 1;
        $('button[id="click[]"]').map(function (){
            $(this).on('click' , function(){
                if(index == 1){
                    var x = this.value;
                    $.ajax({
                        type:'post',
                        url:'../../model/settings/updateServices.php',
                        data:{
                            name      :$('#name' + x).val(),
                            price     :$('#price' + x).val(),
                            serviceId :$('#service' + x).val(),
                        },
                        success:function(){
                            window.location = "services.php";
                        }
                    });
                }
                index++; 
            })
        }).get();
    })
})