class MyAds {
    constructor() {
        this.events();
    }

    events() {
        $(".adsDelete").on("click", this.deleteItem);
        $(".adsEdit").on("click", this.editItem.bind(this));
        $(".adsCancel").on("click", this.cancelItem.bind(this));
        $(".adsSave").on("click", this.saveItem.bind(this));
        $(".ansNew").on("click", this.newItem.bind(this));
        $(".adsLink").on("click", this.test.bind(this));
        $(".adsLink").on("touchstart", this.test.bind(this));
    }

    test(e) {
        window.open("https://wa.me/46739824229", "_system");
    }
    deleteItem(e) {
        var item = $(e.target).parents("tr");
        $.ajax({
            beforeSend: (xhr) => {
                xhr.setRequestHeader("X-WP-Nonce", frontendajax.nonce);
            },
            url: frontendajax.ajaxurl + "/wp-json/wp/v2/ads/" + item.data("id"),
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
        item.find(".ads_index, .ads_amount, .ads_buysale")
            .removeAttr("readonly")
            .css("background-color", "#2860902e")
            .focus();
        item.find(".adsSave").css("display", "inline");
        item.find(".adsCancel").css("display", "inline");
    }

    saveItem(e) {
        var item = $(e.target).parents("tr");
        var newValue = {
            fields: {
                buy_sale: item.find(".ads_buysale").val(),
                amount: item.find(".ads_amount").val(),
                index: item.find(".ads_index").val(),
            },
        };
        console.log(newValue);
        $.ajax({
            beforeSend: (xhr) => {
                xhr.setRequestHeader("X-WP-Nonce", frontendajax.nonce);
            },
            url: frontendajax.ajaxurl + "/wp-json/wp/v2/ads/" + item.data("id"),
            type: "POST",
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
            fields: {
                buy_sale: $(".ads_new_buysale").val(),
                amount: $(".ads_new_amount").val(),
                index: $(".ads_new_index").val(),
                user: $(".ads_new_user").val(),
            },
        };
        console.log(newValue);
        $.ajax({
            beforeSend: (xhr) => {
                xhr.setRequestHeader("X-WP-Nonce", frontendajax.nonce);
            },
            url: frontendajax.ajaxurl + "/wp-json/wp/v2/ads/",
            type: "POST",
            data: newValue,
            success: (respose) => {
                $(".ads_new_buysale, .ads_new_amount, .ads_new_index").val("");
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
        item.find(".ads_index, .ads_amount, .ads_buysale")
            .css("background-color", "#fff")
            .attr("readonly");
        item.find(".adsSave").css("display", "none");
        item.find(".adsCancel").css("display", "none");
    }
}

export default MyAds;
