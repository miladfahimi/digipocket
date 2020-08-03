export default class Like {
    // 1. describe and create /  initiate our object
    constructor() {
        this.events();
    }

    // 2. events
    events() {
        $(".like-button").on("click", this.clickDispatcher.bind(this));
    }

    clickDispatcher(e) {
        var currentLikeItem = $(e.target).closest("a.like-button");
        console.log(currentLikeItem);
        if (currentLikeItem.hasClass("active")) {
            this.dislike();
            this.subtractlikeNumber();
        } else {
            this.like();
            this.addlikeNumber();
        }
    }

    addlikeNumber() {
        var likesNumber = parseInt($(".likes_number").text());
        likesNumber++;
        $(".likes_number").text(likesNumber);
    }
    subtractlikeNumber() {
        var likesNumber = parseInt($(".likes_number").text());
        likesNumber--;
        $(".likes_number").text(likesNumber);
    }

    like() {
        console.log("like function");
        var newValue = {
            title: "&#10084;",
            status: "publish",
            fields: {
                like_id: parseInt($(".new_like_item").text()),
            },
        };
        $.ajax({
            beforeSend: (xhr) => {
                xhr.setRequestHeader("X-WP-Nonce", frontendajax.nonce);
            },
            url: frontendajax.ajaxurl + "/wp-json/wp/v2/like/",
            type: "POST",
            data: newValue,
            success: (response) => {
                $(".the_like_id").text(response.id);
                console.log(response);
                console.log("success like");
            },
            error: (response) => {
                console.log(response);
                console.log("error");
            },
        });
    }

    dislike() {
        console.log("dislike function");
        var item = $(".the_like_id").text();
        $.ajax({
            beforeSend: (xhr) => {
                xhr.setRequestHeader("X-WP-Nonce", frontendajax.nonce);
            },
            url: frontendajax.ajaxurl + "/wp-json/wp/v2/like/" + parseInt(item),
            type: "DELETE",
            success: (respose) => {},
            error: (respose) => {
                console.log(respose);
            },
        });
        console.log("dislike()");
    }
}
