class Login {
    // 1. describe and create /  initiate our object
    constructor() {
        // Get the modal
        this.modal = document.getElementById("loginModal");
        this.modalRegister = document.getElementById("registerModal");

        //sdwitch to register
        this.switchRegister = document.getElementById("redirectToRegister");

        // Get the button that opens the modal
        this.btn = document.getElementById("myLoginBtn");
        this.btnSubmit = document.getElementById("myLoginsubmit");

        // Get the <span> element that closes the modal
        this.span = document.getElementsByClassName("login_close")[0];

        this.scroll = document.body;
        this.win = window;

        this.events();
    }

    events() {
        this.switchRegister.addEventListener("click", () => {
            this.switchToRegister.bind(this)();
        });
        this.span.addEventListener("click", () => {
            this.closeOverlay.bind(this)();
        });
        this.btnSubmit.addEventListener("click", () => {
            this.login.bind(this)();
        });
        if (this.btn) {
            this.btn.addEventListener("click", () => {
                this.openOverlay.bind(this)();
            });
        }
        this.win.addEventListener("click", (event) => {
            if (event.target == this.modal) {
                this.modal.style.display = "none";
                this.scroll.style.overflow = "";
                this.scroll.style.height = "";
            }
        });
    }

    // When the user clicks the button, open the modal
    openOverlay() {
        this.modal.style.display = "block";
        this.scroll.style.overflow = "hidden";
        this.scroll.style.height = "100vh";
    }

    // When the user clicks on <span> (x), close the modal
    closeOverlay() {
        this.modal.style.display = "none";
        this.scroll.style.overflow = "";
        this.scroll.style.height = "";
    }

    switchToRegister() {
        this.modal.style.display = "none";
        this.modalRegister.style.display = "block";
        this.scroll.style.overflow = "hidden";
        this.scroll.style.height = "100vh";
    }

    login() {
        // Perform AJAX login on form submit
        $("#login_form").on("submit", function (e) {
            $("p.status").show().text(ajax_login_object.loadingmessage);
            var data = {
                action: "ajaxlogin", //calls wp_ajax_nopriv_ajaxlogin
                username: $("#username").val(),
                password: $("#password").val(),
                security: $("#security").val(),
            };
            $.ajax({
                type: "POST",
                dataType: "json",
                url: ajax_login_object.ajaxurl,
                data: data,
                success: function (data) {
                    $("p.status").text(data.message);
                    if (data.loggedin == true) {
                        document.location.href = ajax_login_object.redirecturl;
                    }
                },
            });
            e.preventDefault();
        });
    }
}
export default Login;
