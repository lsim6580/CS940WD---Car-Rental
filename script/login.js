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
        $('#loading').removeClass('loading_hidden').addClass('loading');
        $.ajax({
        method: "POST",
        url: "server/login.php",
        dataType: "text",
        data: {type: 'login', username: $("#name-input").val(), password: $("#password-input").val()},
        //processData: false,
       // contentType: false

    }).then(function (data) {
            console.log(data);
            $('#loading').removeClass('loading').addClass('loading_hidden');
            if($.trim(data)=="success")
                window.location.assign("cars.html"); //redirect the page to cars.html
            else{

                $('#loading').removeClass('loading').addClass('loading_hidden');
                $("#login_feedback").html("Invalid username or password"); //show feedback
            }
           //  })
        })
}
function authenticate() {
    var promise = $.Deferred();
    $.ajax({
        method: "POST",
        url: "server/login.php",
        dataType: "text",
        data: {type: 'authenticate'}
    }).then(function (data) {
      promise.resolve(true)
    })
    return promise.promise()
}










