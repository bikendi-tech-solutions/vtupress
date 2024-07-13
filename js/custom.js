function showToast(message,type = "green",time = 3000) {
            var color;
            if(type == "green"){
            color = "#198754";
            }
            else if(type == "red"){
            color = "#dc3545";

            }
            else if(type == "yellow"){
            color = "#ffc107";

            }
            else{
            color = "#0d6efd";

            }


        Toastify({
            text: message,
            duration: time, // duration in milliseconds
            close: true, // show close button
            gravity: "top", // "top" or "bottom"
            position: "right", // "left", "center" or "right"
            backgroundColor: color, // custom background color
        }).showToast();
}
