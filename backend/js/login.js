let username_obj = document.getElementById("username");
let password_obj = document.getElementById("password");

document.addEventListener("DOMContentLoaded", function(){

    //Checa se o user já tem uma sessão.
    //Se tiver, pula o login
    $.ajax({
        url: "backend/php/check_session.php",
        method: "post",
        data: {},

        success: function(data){
            let request_result = JSON.parse(data)

            if (request_result['code'] == 200){
                window.location.href = "frontend/html/dashboard.html"
            }
        }
    })
})

function log_in(){

    $.ajax({
        url: "backend/api/login",
        method: "post",
        data: {
            username: username_obj.value,
            password: password_obj.value
        },

        success: function(data){
            let request_result = JSON.parse(data)

            console.log(request_result)

            if (request_result['code'] == 200){
                window.location.href = "frontend/html/dashboard.html"

            }else if (request_result['code'] == 404){
                console.log("User not found")
                //Rodar toast
            
            }else if (request_result['code'] == 403){
                console.log("Inactive")
                //Rodar toast
            
            }else if (request_result['code'] == 401){
                console.log("Wrong password")
                //Rodar toast

            }
        }
    })
}