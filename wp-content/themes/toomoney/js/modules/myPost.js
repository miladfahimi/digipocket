class MyAds {
    constructor() {
        this.events();
    }

    events() {
        $(".postDelete").on("click", this.deleteItem.bind(this));
        $(".postEdit").on("click", this.editItem.bind(this));
        $(".postCancel").on("click", this.cancelItem.bind(this));
        $(".postSave").on("click", this.saveItem.bind(this));
        $(".postNew").on("click", this.newItem.bind(this));
    }

    deleteItem(e) {
        var item = $(e.target).parents("tr");
        $.ajax({
            beforeSend: (xhr) => {
                xhr.setRequestHeader("X-WP-Nonce", frontendajax.nonce);
            },
            url:
                frontendajax.ajaxurl +
                "/wp-json/wp/v2/posts/" +
                item.data("id"),
            type: "DELETE",
            success: (respose) => {
                item.slideUp();
            },
            error: (respose) => {
                console.log(respose);
            },
        });
    }

    editItem(e) {
        var item = $(e.target).parents("tr");
        item.find(".post_title, .post_content")
            .removeAttr("readonly")
            .css("background-color", "#2860902e")
            .focus();
        item.find(".postSave").css("display", "inline");
        item.find(".postCancel").css("display", "inline");
    }

    saveItem(e) {
        var item = $(e.target).parents("tr");
        var newValue = {
            title: item.find(".post_title").val(),
            content: item.find(".post_content").val(),
            status: "draft",
        };
        console.log(newValue);
        $.ajax({
            beforeSend: (xhr) => {
                xhr.setRequestHeader("X-WP-Nonce", frontendajax.nonce);
            },
            url:
                frontendajax.ajaxurl +
                "/wp-json/wp/v2/posts/" +
                item.data("id"),
            type: "PUT",
            data: newValue,
            success: (respose) => {
                this.makeItReadonly(item);
                console.log("SUCCESS");
                console.log(respose);
            },
            error: (respose) => {
                console.log("FAIL");
                console.log(respose);
            },
        });
    }

    newItem(e) {
        var newValue = {
            title: $(".post_new_title").val(),
            content: $(".post_new_content").val(),
        };
        console.log(newValue);
        $.ajax({
            beforeSend: (xhr) => {
                xhr.setRequestHeader("X-WP-Nonce", frontendajax.nonce);
            },
            url: frontendajax.ajaxurl + "/wp-json/wp/v2/posts/",
            type: "POST",
            data: newValue,
            success: (respose) => {
                $(".post_new_title, .post_new_content").val("");
                console.log(respose);
            },
            error: (respose) => {
                console.log(respose);
            },
        });
    }

    cancelItem(e) {
        var item = $(e.target).parents("tr");
        this.makeItReadonly(item);
    }

    makeItReadonly(item) {
        item.find(".post_title, .post_content")
            .css("background-color", "#fff")
            .attr("readonly");
        item.find(".postSave").css("display", "none");
        item.find(".postCancel").css("display", "none");
    }
}

export default MyAds;
