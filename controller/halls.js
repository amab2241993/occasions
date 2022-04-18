$(function () {
	'use strict';
    $('.halls').submit(function(data){
        data.preventDefault()
        var forms = document.getElementsByClassName('halls');
        Array.prototype.filter.call(forms, function(form) {
            if(form.checkValidity()){
                $.ajax({
                    type:'post',
                    url:'model/halls.php',
                    dataType:'json',
                    data:{
                        name      :$('#name').val(),
                        price     :$('#price').val(),
                        breakfast :$('#breakfast').val(),
                        lunch     :$('#lunch').val(),
                        dinner    :$('#dinner').val()
                    },
                    success:function(){
                    }
                })
            }
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
                        url:'model/updateHalls.php',
                        data:{
                            name      :$('#name' + x).val(),
                            price     :$('#price' + x).val(),
                            breakfast :$('#breakfast' + x).val(),
                            lunch     :$('#lunch' + x).val(),
                            dinner    :$('#dinner' + x).val(),
                            hallId    :$('#hall' + x).val(),
                        },
                        success:function(){
                            window.location = "halls.php";
                        }
                    });
                }
                index++; 
            })
        }).get();
    });
});