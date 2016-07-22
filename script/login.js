$(document).ready(init);

function init(){
    $("#search-button").on("click",login);
    $("#password-input").on("keydown",function(event){maybe_login(event);});
}

function maybe_login(event){
    if (event.keyCode == 13) //ENTER KEY
        login();
}

function login() {
        var check = {type: 'login', username: $("#name-input").val(), password: $("#password-input").val()};
        console.log(check);
        $.ajax({
        method: "POST",
        url: "server/car.php",
        dataType: "text",
        data: {type: 'login', username: $("#name-input").val(), password: $("#password-input").val()},
        success: function (data) {
            console.log(data);
        if($.trim(data)=="success")
            window.location.assign("cars.html"); //redirect the page to cars.html
        else{
            console.log(data);
            $("#loading").attr("class","loading_hidden"); //hide the loading icon
            $("#login_feedback").html("Invalid username or password"); //show feedback
        }
        }
    });
}








