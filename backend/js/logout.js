function log_out(){

    $.ajax({
        url: "../../backend/php/logout.php",
        method: "post",
        data: {},

        success: function(data){

            let request_result = JSON.parse(data)
            
            if (request_result['code'] == 200 || request_result['code'] == 404){
                window.location.href = "../../login.html"

            }

        }
    })

}