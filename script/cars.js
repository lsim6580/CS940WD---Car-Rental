
$(document).ready(init);

function init() {
    // authenticate().then(function (data) {
    //     if(data ==false)
    //     window.location.assign("index.html");
    // })
    $('#logout-link').on('click', function () {
        logout().then(function () {
            window.location.assign("index.html");
        })
    });
    $('#find-car').on('click', function(e){
        $('#user_loading').removeClass('user_loading_hidden').addClass('user_loading');
        e.stopPropagation();
        findCars($('#find-car-input').val())
    })
    getName().then(function (data) {
        console.log(data);
    })

}

function getName() {
    var promise = $.Deferred();
    $.ajax({
        method: "POST",
        url: "server/utility.php",
        dataType: "json",
        data: {type: 'getName'}
    }).then(function (data) {
        console.log(data);
        promise.resolve(data)
    }).then(function (error) {
        console.log(error)
    })
    return promise.promise();
}

function findCars(value){
    console.log(value)
    getCars(value).then(function (data) {
        $('#user_loading').addClass('user_loading_hidden').removeClass('user_loading');
        var template = $('#find-car-template').html();
        var html_maker = new htmlMaker(template);
        var html = html_maker.getHTML(data);
        $('#search_results').html(html);
    })
}

function getCars(value) {
    var promise = $.Deferred();
    $.ajax({
        method: "POST",
        url: "server/utility.php",
        dataType: "json",
        data: {type: 'getCars', value: value}
    }).then(function (data) {
        promise.resolve(data)
    })
    return promise.promise();
}

function authenticate() {
    var promise = $.Deferred();
    $.ajax({
        method: "POST",
        url: "server/utility.php",
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
        url: "server/utility.php",
        dataType: "text",
        data: {type: 'logout'}
    }).then(function (data) {
        promise.resolve(data)
    })
    return promise.promise()}
