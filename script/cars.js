
$(document).ready(init);

function init() {
    getAllCars().then(function (data) {
        console.log(data.length);
        console.log("HERE");
    })
    // authenticate().then(function (data) {
    //     if(data ==false)
    //     window.location.assign("index.html");
    // })
 $('#logout-link').on('click', function () {
     logout().then(function(){
         window.location.assign("index.html");
     })
 })
$('#find-car').on('click', function () {
    getAllCars().then(function (data) {
        var template = $('#find-car-template').html();
        var html_maker = new htmlMaker(template);
        var html = html_maker.getHTML(data);
        $('#search_results').html(html);
    })
})
}

function getAllCars() {
    var promise = $.Deferred();
    $.ajax({
        method: "POST",
        url: "server/car.php",
        dataType: "json",
        data: {type: 'getAllCars'}
    }).then(function (data) {
        promise.resolve(data)
    })
    return promise.promise();
}

function authenticate() {
    var promise = $.Deferred();
    $.ajax({
        method: "POST",
        url: "server/car.php",
        dataType: "text",
        data: {type: 'authenticate'}
    }).then(function (data) {
        promise.resolve(data)
    })
    return promise.promise()
}

function logout() {
    var promise = $.Deferred();
    $.ajax({
        method: "POST",
        url: "server/car.php",
        dataType: "text",
        data: {type: 'logout'}
    }).then(function (data) {
        promise.resolve(data)
    })
    return promise.promise()}