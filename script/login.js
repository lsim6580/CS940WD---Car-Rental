$(document).ready(init);

function init(){
    // authenticate().then(function (data) {
    //     if(data == true)
    //         window.location.assign("cars.html");
    //
    // })
    $("#search-button").on("click",login);
    $("#password-input").on("keydown",function(event){maybe_login(event);});
}

function maybe_login(event){
    if (event.keyCode == 13) //ENTER KEY
        login();
}

function login() {
        $.ajax({
        method: "POST",
        url: "server/car.php",
        dataType: "text",
        data: {type: 'login', username: $("#name-input").val(), password: $("#password-input").val()},
        //processData: false,
       // contentType: false

    }).then(function (data) {
            // var result = authenticate();
            // result.then(function (other) {
            //     console.log(other);
            if(data)
                window.location.assign("cars.html"); //redirect the page to cars.html
            else{
                console.log(data);
                $("#loading").attr("class","loading_hidden"); //hide the loading icon
                $("#login_feedback").html("Invalid username or password"); //show feedback
            }
           //  })
        })
}
function authenticate() {
    var promise = $.Deferred();
    $.ajax({
        method: "POST",
        url: "server/car.php",
        dataType: "text",
        data: {type: 'authenticate'}
    }).then(function (data) {
      promise.resolve(true)
    })
    return promise.promise()
}










