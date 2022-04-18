$(function () {
	'use strict';
    var quantitys;
    $('#subCategory').submit(function(data){
        data.preventDefault()
        var form = document.getElementById('subCategory');
        if(form.checkValidity()){
            quantitys = $('#quantity').val() == "" ? 0 :$('#quantity').val()
            $.ajax({
                type:'post',
                url:'../../model/settings/subCategories.php',
                data:{
                    parent   : $('#parent').val(),
                    name     : $('#name').val(),
                    quantity : quantitys,
                },
                success:function(){
                    window.location.reload()
                    alert("success");
                }
            })
        }
    })

    $('#parent').on('change',function(){
        
        $.ajax({
            type:'POST',
            url:'../../model/settings/subCategory1.php',
            data:{parent:$('#parent').val()},
            dataType:'json'
        }).done(function(data){
            $("#quantity").attr({"max" : parseInt(data)})
            $.ajax({
                type:'POST',
                url:'../../model/settings/subCategory.php',
                data:{parent:$('#parent').val()},
                dataType:'json'
            }).done(function(data){
                console.log(data)
                var html = ''
                $.each(data, function(index){
                    html += "<tr>"
                    html += "<td>"
                    html += index + 1
                    html += "</td>"
                    html += "<td>"
                    html += data[index].category
                    html += "</td>"
                    html += "<td>"
                    html += data[index].customer_price
                    html += "</td>"
                    html += "<td>"
                    html += data[index].companion_price
                    html += "</td>"
                    html += "<td>"
                    html += data[index].employee_price
                    html += "</td>"
                    html += "<td>"
                    html += data[index].quantity
                    html += "</td>"
                    html += "<td>"
                    html += "<i class='fa fa-edit edit'></i>"
                    html += "<i class='fa fa-remove remove'></i>"
                    html += "</td>"
                    html += "</tr>"
                })
                $('tbody').html(html)
            })
        })
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