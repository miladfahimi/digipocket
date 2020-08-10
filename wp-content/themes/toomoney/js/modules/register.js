class Register {
    // 1. describe and create /  initiate our object
    constructor() {
        // Get the modal
        this.modal = document.getElementById("registerModal");
        this.modalLogin = document.getElementById("loginModal");

        //switch to login
        this.switchRegister = document.getElementById("redirectToLogin");

        // Get the button that opens the modal
        this.btn = document.getElementById("myRegisterBtn");

        this.btnRegister = document.getElementById("myRegistersubmit");

        // Get the <span> element that closes the modal
        this.span = document.getElementsByClassName("register_close")[0];

        this.scroll = document.body;
        this.win = window;

        this.events();
    }

    events() {
        this.switchRegister.addEventListener("click", () => {
            this.switchToLogin.bind(this)();
        });
        this.span.addEventListener("click", () => {
            this.closeOverlay.bind(this)();
        });
        this.btnRegister.addEventListener("click", () => {
            this.register.bind(this)();
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

    switchToLogin() {
        this.modal.style.display = "none";
        this.modalLogin.style.display = "block";
        this.scroll.style.overflow = "hidden";
        this.scroll.style.height = "100vh";
    }

    register() {
        // Perform AJAX login on form submit
        $("p.registration_status")
            .show()
            .text(ajax_register_object.loadingmessage);
        var data = {
            action: "ajaxregister",
            new_user_name: $("#new-username").val(),
            new_user_email: $("#new-useremail").val(),
            new_user_first_name: $("#new-first-name").val(),
            new_user_password: $("#new-userpassword").val(),
            security: $("#security1").val(),
        };
        $.ajax({
            type: "POST",
            dataType: "json",
            url: ajax_register_object.ajaxregisterurl,
            data: data,
            success: function (data) {
                $("p.registration_status").text(data.message);
                if (data.loggedin == true) {
                    document.location.href = ajax_register_object.redirecturl;
                }
            },
            error: function (data) {
                $("p.registration_status").text(data.message);
            },
        });
    }
}
export default Register;
