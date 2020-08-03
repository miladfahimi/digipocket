export default class Search {
    // 1. describe and create /  initiate our object
    constructor() {
        this.openButton = document.querySelector(".search_btn");
        this.closeButton = document.querySelector("#search_form_close");
        this.searchOverlay = document.querySelector("#search_form");
        this.searchInput = document.querySelector("#main_search_input");
        this.events();
    }

    // 2. events
    events() {
        this.openButton.addEventListener("click", () => {
            this.openOverlay.bind(this)();
        });
        this.closeButton.addEventListener("click", () => {
            this.closeOverlay.bind(this)();
        });
    }

    openOverlay() {
        this.searchOverlay.classList.add("in");
        this.searchOverlay.classList.add("open_search_box");
        setTimeout(() => this.searchInput.focus(), 301);
    }

    closeOverlay() {
        this.searchOverlay.classList.remove("in");
        this.searchOverlay.classList.remove("open_search_box");
    }
}
