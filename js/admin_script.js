document.addEventListener("DOMContentLoaded", function(){
    
    if (document.getElementById("php_notification")) {
        show_notification("success", document.getElementById("php_notification").value, 5000);
    }
    
    if (document.getElementById("php_err_notification")) {
        show_notification("error", document.getElementById("php_err_notification").value, 5000);
    }
    
    if (document.getElementById("notification_close")) {
        document.getElementById("notification_close").addEventListener('click', function () {
            document.getElementById("notification").classList.add("d-none");
        });
    }
});


function show_notification(type, message, timeout) {
    document.getElementById("notification_content").innerHTML = message;

    let notification = document.getElementById("notification");

    if (type == "success")
        notification.classList.add("noti-success");
    else if (type == "error")
        notification.classList.add("noti-error");


    notification.classList.remove("d-none");
    notification.classList.add("d-flex");

    if (type != "error" && timeout > 0) {
        setTimeout(function () {
            notification.classList.add("fadeout")
        }, timeout);
        
        setTimeout(function () {
            notification.classList.add("d-none");
            notification.classList.remove("d-flex");
            notification.classList.remove("fadeout");
        }, timeout + 1000);
    }
}