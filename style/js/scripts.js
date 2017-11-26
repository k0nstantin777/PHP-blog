$(document).ready(function(){
	
	$(window).resize(function(){
		console.log(window.innerWidth);
	});
	
	$('header nav .show_menu').click(function(){
		$('header nav ul').slideToggle(500);
	});
});

//var login = document.getElementById('login');
//
//login.onkeyup = login.onchange = function (){
// 
// //  var xhr = new XMLHttpRequest();
//  
//  xhr.open('POST', 'ajax/login', true);
//  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
//  var body = 'login='+ login.value;
//  //alert (body);
//  xhr.send(body);
//  
//  xhr.onreadystatechange = function (){
//      
//      //var p = ;
//     
//      if (document.getElementById('error')){
//          var p = document.getElementById('error');
//          p.remove();
//      } 
//      
//      var p1 = document.createElement('p');
//            
//      console.log(xhr.responseText);
//      //var p = document.getElementById('error');
//      
//      p1.innerHTML = xhr.responseText;
//      p1.id = 'error';
//      login.insertAdjacentElement("afterEnd", p1);
//      
//      
////      p.innerHTML = xhr.responseText;
//  };
//     
//};

$('#login').keyup(func);
$('#login').change(func);

function func(){
    var data = 'login='+ this.value;
    
//    $.post('ajax/login', data, function (d){
//        if ($('#error').length){
//            $('#error').remove();
//        }
//        $('#login').after('<p id=error>'+ d + '</p>');
//    });
    
    $.ajax({
        url:'ajax/login',
        data: data,
        method: 'post',
        beforeSend: function (){
            if ($('#error').length){
                $('#error').remove();
            }
            $('#login').after('<p id=error>'+ 'Загружаю' + '</p>');
            $('body').css('background-color', 'grey');
        },
        success: function (d){
            if ($('#error').length){
                $('#error').remove();
            }
            $('#login').after('<p id=error>'+ d + '</p>');
            $('body').css('background-color', '');
        },
        timeout: 1500
    });
    
};