var us = "";
var sek = "";
var nok = "";
var dkk = "";
class Index {
    // 1. describe and create /  initiate our object
    constructor() {
        // Get the modal
        us = document.querySelector("#index-us");
        sek = document.getElementById("index-sek");
        nok = document.getElementById("index-nok");
        dkk = document.getElementById("index-dkk");
        this.events();
    }

    events() {
        setInterval(function () {
            $.ajax({
                url: frontendajax.ajaxurl + "/wp-json/wp/v2/index/",
                type: "GET",
                success: (respose) => {
                    us.innerHTML = respose[0].acf.usd_sale;
                    sek.innerHTML = respose[0].acf.sek_buy;
                    nok.innerHTML = respose[0].acf.usd_buy;
                    dkk.innerHTML = respose[0].acf.sek_sale;
                },
                error: (respose) => {
                    console.log(
                        "Something goes wrong in getting the index values! Please contact admin!"
                    );
                },
            });
        }, 3000);
    }
}
export default Index;
