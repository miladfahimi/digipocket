export default class Login {
    // 1. describe and create /  initiate our object
    constructor() {
        // Get the modal
        this.modal = document.getElementById("loginModal");

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

    login() {
        var loginForm = $("#login_form").serialize();
        loginForm += "&action=custom_login&param=login_test";
        var ajax_url = $("#adminAjaxUrl").text();
        var newValue = {
            user_login: $("#username").val(),
            user_password: $("#password").val(),
        };
        $.ajax({
            url: ajax_url,
            type: "POST",
            data: loginForm,
            success: (respose) => {
                console.log(respose);
            },
            error: (respose) => {
                console.log(respose);
            },
        });
    }
}
