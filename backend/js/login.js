let username_obj = document.getElementById("username");
let password_obj = document.getElementById("password");

function log_in(){

    $.ajax({
        url: "backend/php/login.php",
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