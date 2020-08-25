import Search from "./modules/search.js";
import Login from "./modules/login.js";
import Register from "./modules/register.js";
import Ads from "./modules/myAds.js";
import Post from "./modules/myPost.js";
import Like from "./modules/like.js";
import Index from "./modules/index.js";

const search = new Search();
const register = new Register();
const login = new Login();
const ads = new Ads();
const post = new Post();
const like = new Like();
const index = new Index();

// ---------------------------------------------------------------------------------
// THIS IS A PART OF SCRIPT FOR STYLING USER PANEL SHOULD BE MOVED TO STYLING PART!
$(document).ready(function () {
    if ($("div.bhoechie-tab-menu>a")) {
        $("div.bhoechie-tab-menu>a").click(function (e) {
            e.preventDefault();
            $(this).siblings("a.active").removeClass("active");
            $(this).addClass("active");
            var index = $(this).index();
            $("div.bhoechie-tab>div.bhoechie-tab-content").removeClass(
                "active"
            );
            $("div.bhoechie-tab>div.bhoechie-tab-content")
                .eq(index)
                .addClass("active");
        });
    }
});
// ---------------------------------------------------------------------------------

let button = document.querySelector(".like-button");
if (button) {
    button.addEventListener("click", function (e) {
        e.preventDefault();
        this.classList.toggle("active");
        this.classList.add("animated");
        generateClones(this);
    });
}

function generateClones(button) {
    let clones = randomInt(2, 4);
    for (let it = 1; it <= clones; it++) {
        let clone = button.querySelector("svg").cloneNode(true),
            size = randomInt(5, 16);
        button.appendChild(clone);
        clone.setAttribute("width", size);
        clone.setAttribute("height", size);
        clone.style.position = "absolute";
        clone.style.transition =
            "transform 0.5s cubic-bezier(0.12, 0.74, 0.58, 0.99) 0.3s, opacity 1s ease-out .5s";
        let animTimeout = setTimeout(function () {
            clearTimeout(animTimeout);
            clone.style.transform =
                "translate3d(" +
                plusOrMinus() * randomInt(10, 25) +
                "px," +
                plusOrMinus() * randomInt(10, 25) +
                "px,0)";
            clone.style.opacity = 0;
        }, 1);
        let removeNodeTimeout = setTimeout(function () {
            clone.parentNode.removeChild(clone);
            clearTimeout(removeNodeTimeout);
        }, 900);
        let removeClassTimeout = setTimeout(function () {
            button.classList.remove("animated");
        }, 600);
    }
}

function plusOrMinus() {
    return Math.random() < 0.5 ? -1 : 1;
}

function randomInt(min, max) {
    return Math.floor(Math.random() * (max - min + 1) + min);
}
