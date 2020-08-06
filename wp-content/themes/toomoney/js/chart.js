Highcharts.getJSON(
    "https://cdn.jsdelivr.net/gh/highcharts/highcharts@v7.0.0/samples/data/new-intraday.json",
    function (data) {
        chart(data);
    }
);
function chart(data) {
    $("#chart2-container").highcharts({
        // Create the chart

        // create the chart

        title: {
            text: "AAPL stock price by minute",
        },

        subtitle: {
            text: "",
        },

        xAxis: {
            breaks: [
                {
                    // Nights
                    from: Date.UTC(2011, 9, 6, 16),
                    to: Date.UTC(2011, 9, 7, 8),
                    repeat: 24 * 36e5,
                },
                {
                    // Weekends
                    from: Date.UTC(2011, 9, 7, 16),
                    to: Date.UTC(2011, 9, 10, 8),
                    repeat: 7 * 24 * 36e5,
                },
            ],
        },

        rangeSelector: {
            buttons: [
                {
                    type: "hour",
                    count: 1,
                    text: "1h",
                },
                {
                    type: "day",
                    count: 1,
                    text: "1D",
                },
                {
                    type: "all",
                    count: 1,
                    text: "All",
                },
            ],
            selected: 0,
            inputEnabled: false,
        },

        series: [
            {
                name: "AAPL",
                type: "area",
                data: data,
                gapSize: 5,
                tooltip: {
                    valueDecimals: 2,
                },
                fillColor: {
                    linearGradient: {
                        x1: 0,
                        y1: 0,
                        x2: 0,
                        y2: 1,
                    },
                    stops: [
                        [1, Highcharts.getOptions().colors[8]],
                        [
                            1,
                            Highcharts.color(Highcharts.getOptions().colors[4])
                                .setOpacity(0)
                                .get("rgba"),
                        ],
                    ],
                },
                threshold: null,
            },
        ],
    });
    $("#chart1-container").highcharts({
        // Create the chart

        // create the chart

        title: {
            text: "AAPL stock price by minute",
        },

        subtitle: {
            text: "",
        },

        xAxis: {
            breaks: [
                {
                    // Nights
                    from: Date.UTC(2011, 9, 6, 16),
                    to: Date.UTC(2011, 9, 7, 8),
                    repeat: 24 * 36e5,
                },
                {
                    // Weekends
                    from: Date.UTC(2011, 9, 7, 16),
                    to: Date.UTC(2011, 9, 10, 8),
                    repeat: 7 * 24 * 36e5,
                },
            ],
        },

        rangeSelector: {
            buttons: [
                {
                    type: "hour",
                    count: 1,
                    text: "1h",
                },
                {
                    type: "day",
                    count: 1,
                    text: "1D",
                },
                {
                    type: "all",
                    count: 1,
                    text: "All",
                },
            ],
            selected: 0,
            inputEnabled: false,
        },

        series: [
            {
                name: "AAPL",
                type: "area",
                data: data,
                gapSize: 5,
                tooltip: {
                    valueDecimals: 2,
                },
                fillColor: {
                    linearGradient: {
                        x1: 0,
                        y1: 0,
                        x2: 0,
                        y2: 1,
                    },
                    stops: [
                        [1, Highcharts.getOptions().colors[8]],
                        [
                            1,
                            Highcharts.color(Highcharts.getOptions().colors[4])
                                .setOpacity(0)
                                .get("rgba"),
                        ],
                    ],
                },
                threshold: null,
            },
        ],
    });
    $("#chart3-container").highcharts({
        // Create the chart

        // create the chart

        title: {
            text: "AAPL stock price by minute",
        },

        subtitle: {
            text: "",
        },

        xAxis: {
            breaks: [
                {
                    // Nights
                    from: Date.UTC(2011, 9, 6, 16),
                    to: Date.UTC(2011, 9, 7, 8),
                    repeat: 24 * 36e5,
                },
                {
                    // Weekends
                    from: Date.UTC(2011, 9, 7, 16),
                    to: Date.UTC(2011, 9, 10, 8),
                    repeat: 7 * 24 * 36e5,
                },
            ],
        },

        rangeSelector: {
            buttons: [
                {
                    type: "hour",
                    count: 1,
                    text: "1h",
                },
                {
                    type: "day",
                    count: 1,
                    text: "1D",
                },
                {
                    type: "all",
                    count: 1,
                    text: "All",
                },
            ],
            selected: 0,
            inputEnabled: false,
        },

        series: [
            {
                name: "AAPL",
                type: "area",
                data: data,
                gapSize: 5,
                tooltip: {
                    valueDecimals: 2,
                },
                fillColor: {
                    linearGradient: {
                        x1: 0,
                        y1: 0,
                        x2: 0,
                        y2: 1,
                    },
                    stops: [
                        [1, Highcharts.getOptions().colors[8]],
                        [
                            1,
                            Highcharts.color(Highcharts.getOptions().colors[4])
                                .setOpacity(0)
                                .get("rgba"),
                        ],
                    ],
                },
                threshold: null,
            },
        ],
    });
    $("#chart4-container").highcharts({
        // Create the chart

        // create the chart

        title: {
            text: "AAPL stock price by minute",
        },

        subtitle: {
            text: "",
        },

        xAxis: {
            breaks: [
                {
                    // Nights
                    from: Date.UTC(2011, 9, 6, 16),
                    to: Date.UTC(2011, 9, 7, 8),
                    repeat: 24 * 36e5,
                },
                {
                    // Weekends
                    from: Date.UTC(2011, 9, 7, 16),
                    to: Date.UTC(2011, 9, 10, 8),
                    repeat: 7 * 24 * 36e5,
                },
            ],
        },

        rangeSelector: {
            buttons: [
                {
                    type: "hour",
                    count: 1,
                    text: "1h",
                },
                {
                    type: "day",
                    count: 1,
                    text: "1D",
                },
                {
                    type: "all",
                    count: 1,
                    text: "All",
                },
            ],
            selected: 0,
            inputEnabled: false,
        },

        series: [
            {
                name: "AAPL",
                type: "area",
                data: data,
                gapSize: 5,
                tooltip: {
                    valueDecimals: 2,
                },
                fillColor: {
                    linearGradient: {
                        x1: 0,
                        y1: 0,
                        x2: 0,
                        y2: 1,
                    },
                    stops: [
                        [1, Highcharts.getOptions().colors[8]],
                        [
                            1,
                            Highcharts.color(Highcharts.getOptions().colors[4])
                                .setOpacity(0)
                                .get("rgba"),
                        ],
                    ],
                },
                threshold: null,
            },
        ],
    });
}
