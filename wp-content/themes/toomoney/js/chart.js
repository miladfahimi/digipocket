Highcharts.getJSON(
    "https://api.marketstack.com/v1/tickers/aapl/eod?access_key=fb80b6b5f08ec0da9e0b3c99672d5802",
    function (data) {
        var mydata = [];
        $.each(data.data.eod, function (dataIndex, dataObj) {
            mydata.push([
                Date.parse(dataObj["date"]),
                dataObj["open"],
                dataObj["high"],
                dataObj["low"],
                dataObj["close"],
            ]);
            // console.log(
            //     dataObj["high"]
            //     //`Ticker ${stockData["symbol"]} has a day high of ${stockData["high"]}on ${stockData["date"]}`
            // );
        });
        chartId = "#chart4-container";
        chart(mydata, chartId, "Apple");
        console.log(mydata);
    }
);
Highcharts.getJSON(
    "https://api.marketstack.com/v1/tickers/goog/eod?access_key=fb80b6b5f08ec0da9e0b3c99672d5802",
    function (data) {
        var mydata = [];
        $.each(data.data.eod, function (dataIndex, dataObj) {
            mydata.push([
                Date.parse(dataObj["date"]),
                dataObj["open"],
                dataObj["high"],
                dataObj["low"],
                dataObj["close"],
            ]);
            // console.log(
            //     dataObj["high"]
            //     //`Ticker ${stockData["symbol"]} has a day high of ${stockData["high"]}on ${stockData["date"]}`
            // );
        });
        chartId = "#chart2-container";
        chart(mydata, chartId, "Google");
        console.log(mydata);
    }
);
Highcharts.getJSON(
    "https://api.marketstack.com/v1/tickers/amzn/eod?access_key=fb80b6b5f08ec0da9e0b3c99672d5802",
    function (data) {
        var mydata = [];
        $.each(data.data.eod, function (dataIndex, dataObj) {
            mydata.push([
                Date.parse(dataObj["date"]),
                dataObj["open"],
                dataObj["high"],
                dataObj["low"],
                dataObj["close"],
            ]);
            // console.log(
            //     dataObj["high"]
            //     //`Ticker ${stockData["symbol"]} has a day high of ${stockData["high"]}on ${stockData["date"]}`
            // );
        });
        chartId = "#chart3-container";
        chart(mydata, chartId, "Amazon");
        console.log(mydata);
    }
);
Highcharts.getJSON(
    "https://api.marketstack.com/v1/tickers/MSFT/eod?access_key=fb80b6b5f08ec0da9e0b3c99672d5802",
    function (data) {
        var mydata = [];
        $.each(data.data.eod, function (dataIndex, dataObj) {
            mydata.push([
                Date.parse(dataObj["date"]),
                dataObj["open"],
                dataObj["high"],
                dataObj["low"],
                dataObj["close"],
            ]);
            // console.log(
            //     dataObj["high"]
            //     //`Ticker ${stockData["symbol"]} has a day high of ${stockData["high"]}on ${stockData["date"]}`
            // );
        });
        chartId = "#chart1-container";
        chart(mydata, chartId, "Microsoft");
        console.log(mydata);
    }
);

function chart(data, chartId, title) {
    $(function () {
        $(document).ready(function () {
            Highcharts.setOptions({
                global: {
                    useUTC: false,
                },
            });
        });
    });

    // split the data set into ohlc and volume
    var ohlc = [],
        volume = [],
        dataLength = data.length,
        // set the allowed units for data grouping
        groupingUnits = [
            [
                "week", // unit name
                [1], // allowed multiples
            ],
            ["month", [1, 2, 3, 4, 6]],
        ],
        i = 0;

    for (i; i < dataLength; i += 1) {
        ohlc.push([
            data[i][0], // the date
            data[i][1], // open
            data[i][2], // high
            data[i][3], // low
            data[i][4], // close
        ]);

        volume.push([
            data[i][0], // the date
            data[i][5], // the volume
        ]);
    }

    // create the chart
    $(chartId).highcharts({
        rangeSelector: {
            selected: 1,
        },

        title: {
            text: title,
        },

        yAxis: [
            {
                labels: {
                    align: "left",
                    x: 0,
                },
                title: {
                    text: "",
                },
                height: "100%",
                lineWidth: 2,
                resize: {
                    enabled: true,
                },
            },
            {
                labels: {
                    align: "right",
                    x: -8,
                },
                title: {
                    text: "",
                },
                height: "0%",
                offset: 0,
                lineWidth: 2,
            },
        ],
        xAxis: [
            {
                labels: {
                    align: "left",
                    x: 0,
                },
                title: {
                    text: "",
                },
                height: "100%",
                lineWidth: 2,
                resize: {
                    enabled: false,
                },
            },
            {
                labels: {
                    align: "right",
                    x: 0,
                },
                title: {
                    text: "",
                },
                height: "0%",
                offset: 0,
                lineWidth: 2,
            },
        ],

        tooltip: {
            split: false,
        },

        series: [
            {
                type: "line",
                enable: false,
                name: "",
                data: ohlc,
                dataGrouping: {
                    units: groupingUnits,
                },
            },
        ],
    });
}
