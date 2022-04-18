$(function () {
	'use strict';
    $('#controllers').submit(function(data){
        data.preventDefault()
        var form = document.getElementById('controllers');
        if(form.checkValidity()){
            $.ajax({
                type:'post',
                url:'../../model/settings/controllers.php',
                data:{
                    parent :$('#parent').val(),
                    name   :$('#name').val()
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
            url:'../../model/settings/control.php',
            data:{parent:$('#parent').val()},
            dataType:'json'
        }).done(function(data){
            console.log(data)
            var html = ''
            $.each(data, function(index){
                html += "<tr>"
                html += "<td>"
                html += data[index].code
                html += "</td>"
                html += "<td>"
                html += data[index].name
                html += "</td>"
                html += "<td>"
                html += "<i class='fa fa-edit edit'></i>"
                html += "<i class='fa fa-remove remove'></i>"
                html += "</td>"
                html += "</tr>"
            })
            $('tbody').html(html)
        }).fail(function(data , data1,data2){
            console.log(data)
            console.log(data1)
            console.log(data2)
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