
    var x = document.getElementById("login");
    var y = document.getElementById("register");

    function login() {
        x.style.left = "0";
        y.style.right = "-100%";
        x.style.opacity = "1";
        y.style.opacity = "0";
    }

    function register() {
        x.style.left = "-100%";
        y.style.right = "0";
        x.style.opacity = "0";
        y.style.opacity = "1";
    }

    
