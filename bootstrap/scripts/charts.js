var Charts = function () {

    return {
        //main function to initiate the module

        init: function () {

            App.addResponsiveHandler(function () {
                 Charts.initPieCharts(); 
            });
            
        },

        initCharts: function () {

            if (!jQuery.plot) {
                return;
            }

            var data = [];
            var totalPoints = 250;

            // random data generator for plot charts

            function getRandomData() {
                if (data.length > 0) data = data.slice(1);
                // do a random walk
                while (data.length < totalPoints) {
                    var prev = data.length > 0 ? data[data.length - 1] : 50;
                    var y = prev + Math.random() * 10 - 5;
                    if (y < 0) y = 0;
                    if (y > 100) y = 100;
                    data.push(y);
                }
                // zip the generated y values with the x values
                var res = [];
                for (var i = 0; i < data.length; ++i) res.push([i, data[i]])
                return res;
            }

            //Basic Chart

            function chart1() {
                var d1 = [];
                for (var i = 0; i < Math.PI * 2; i += 0.25)
                    d1.push([i, Math.sin(i)]);

                var d2 = [];
                for (var i = 0; i < Math.PI * 2; i += 0.25)
                    d2.push([i, Math.cos(i)]);

                var d3 = [];
                for (var i = 0; i < Math.PI * 2; i += 0.1)
                    d3.push([i, Math.tan(i)]);

                $.plot($("#chart_1"), [{
                            label: "sin(x)",
                            data: d1
                        }, {
                            label: "cos(x)",
                            data: d2
                        }, {
                            label: "tan(x)",
                            data: d3
                        }
                    ], {
                        series: {
                            lines: {
                                show: true
                            },
                            points: {
                                show: true
                            }
                        },
                        xaxis: {
                            ticks: [0, [Math.PI / 2, "\u03c0/2"],
                                [Math.PI, "\u03c0"],
                                [Math.PI * 3 / 2, "3\u03c0/2"],
                                [Math.PI * 2, "2\u03c0"]
                            ]
                        },
                        yaxis: {
                            ticks: 10,
                            min: -2,
                            max: 2
                        },
                        grid: {
                            backgroundColor: {
                                colors: ["#fff", "#eee"]
                            }
                        }
                    });

            }

            //Interactive Chart


            //Tracking Curves

            function chart3() {
                //tracking curves:

                var sin = [],
                    cos = [];
                for (var i = 0; i < 14; i += 0.1) {
                    sin.push([i, Math.sin(i)]);
                    cos.push([i, Math.cos(i)]);
                }

                plot = $.plot($("#chart_3"), [{
                            data: sin,
                            label: "sin(x) = -0.00"
                        }, {
                            data: cos,
                            label: "cos(x) = -0.00"
                        }
                    ], {
                        series: {
                            lines: {
                                show: true
                            }
                        },
                        crosshair: {
                            mode: "x"
                        },
                        grid: {
                            hoverable: true,
                            autoHighlight: false
                        },
                        yaxis: {
                            min: -1.2,
                            max: 1.2
                        }
                    });

                var legends = $("#chart_3 .legendLabel");
                legends.each(function () {
                    // fix the widths so they don't jump around
                    $(this).css('width', $(this).width());
                });

                var updateLegendTimeout = null;
                var latestPosition = null;

                function updateLegend() {
                    updateLegendTimeout = null;

                    var pos = latestPosition;

                    var axes = plot.getAxes();
                    if (pos.x < axes.xaxis.min || pos.x > axes.xaxis.max || pos.y < axes.yaxis.min || pos.y > axes.yaxis.max) return;

                    var i, j, dataset = plot.getData();
                    for (i = 0; i < dataset.length; ++i) {
                        var series = dataset[i];

                        // find the nearest points, x-wise
                        for (j = 0; j < series.data.length; ++j)
                            if (series.data[j][0] > pos.x) break;

                            // now interpolate
                        var y, p1 = series.data[j - 1],
                            p2 = series.data[j];
                        if (p1 == null) y = p2[1];
                        else if (p2 == null) y = p1[1];
                        else y = p1[1] + (p2[1] - p1[1]) * (pos.x - p1[0]) / (p2[0] - p1[0]);

                        legends.eq(i).text(series.label.replace(/=.*/, "= " + y.toFixed(2)));
                    }
                }

                $("#chart_3").bind("plothover", function (event, pos, item) {
                    latestPosition = pos;
                    if (!updateLegendTimeout) updateLegendTimeout = setTimeout(updateLegend, 50);
                });
            }

            //Dynamic Chart

            function chart4() {
                //server load
                var options = {
                    series: {
                        shadowSize: 1
                    },
                    lines: {
                        show: true,
                        lineWidth: 0.5,
                        fill: true,
                        fillColor: {
                            colors: [{
                                    opacity: 0.1
                                }, {
                                    opacity: 1
                                }
                            ]
                        }
                    },
                    yaxis: {
                        min: 0,
                        max: 100,
                        tickFormatter: function (v) {
                            return v + "%";
                        }
                    },
                    xaxis: {
                        show: false
                    },
                    colors: ["#6ef146"],
                    grid: {
                        tickColor: "#a8a3a3",
                        borderWidth: 0
                    }
                };

                var updateInterval = 30;
                var plot = $.plot($("#chart_4"), [getRandomData()], options);

                function update() {
                    plot.setData([getRandomData()]);
                    plot.draw();
                    setTimeout(update, updateInterval);
                }
                update();
            }

            //bars with controls

            function chart5() {
                var d1 = [];
                for (var i = 0; i <= 10; i += 1)
                    d1.push([i, parseInt(Math.random() * 30)]);

                var d2 = [];
                for (var i = 0; i <= 10; i += 1)
                    d2.push([i, parseInt(Math.random() * 30)]);

                var d3 = [];
                for (var i = 0; i <= 10; i += 1)
                    d3.push([i, parseInt(Math.random() * 30)]);

                var stack = 0,
                    bars = true,
                    lines = false,
                    steps = false;

                function plotWithOptions() {
                    $.plot($("#chart_5"), [d1, d2, d3], {
                            series: {
                                stack: stack,
                                lines: {
                                    show: lines,
                                    fill: true,
                                    steps: steps
                                },
                                bars: {
                                    show: bars,
                                    barWidth: 0.6
                                }
                            }
                        });
                }

                $(".stackControls input").click(function (e) {
                    e.preventDefault();
                    stack = $(this).val() == "With stacking" ? true : null;
                    plotWithOptions();
                });
                $(".graphControls input").click(function (e) {
                    e.preventDefault();
                    bars = $(this).val().indexOf("Bars") != -1;
                    lines = $(this).val().indexOf("Lines") != -1;
                    steps = $(this).val().indexOf("steps") != -1;
                    plotWithOptions();
                });

                plotWithOptions();
            }

            //graph
			
			//已关闭,前面未调用ID时,后面将停止,因此已放到调用页面
            //chart1();
            //chart2();
            //chart3();
            //chart4();
            //chart5();

        },

        initPieCharts: function () {

            var data = [];
            var series = Math.floor(Math.random() * 10) + 1;
            series = series < 5 ? 5 : series;
            
            for (var i = 0; i < series; i++) {
                data[i] = {
                    label: "Series" + (i + 1),
                    data: Math.floor(Math.random() * 100) + 1
                }
            }

            // DEFAULT
            $.plot($("#pie_chart"), data, {
                    series: {
                        pie: {
                            show: true
                        }
                    }
                });

            // GRAPH 1
            $.plot($("#pie_chart_1"), data, {
                    series: {
                        pie: {
                            show: true
                        }
                    },
                    legend: {
                        show: false
                    }
                });

            // GRAPH 2
            $.plot($("#pie_chart_2"), data, {
                    series: {
                        pie: {
                            show: true,
                            radius: 1,
                            label: {
                                show: true,
                                radius: 1,
                                formatter: function (label, series) {
                                    return '<div style="font-size:8pt;text-align:center;padding:2px;color:white;">' + label + '<br/>' + Math.round(series.percent) + '%</div>';
                                },
                                background: {
                                    opacity: 0.8
                                }
                            }
                        }
                    },
                    legend: {
                        show: false
                    }
                });

            // GRAPH 3
            $.plot($("#pie_chart_3"), data, {
                    series: {
                        pie: {
                            show: true,
                            radius: 1,
                            label: {
                                show: true,
                                radius: 3 / 4,
                                formatter: function (label, series) {
                                    return '<div style="font-size:8pt;text-align:center;padding:2px;color:white;">' + label + '<br/>' + Math.round(series.percent) + '%</div>';
                                },
                                background: {
                                    opacity: 0.5
                                }
                            }
                        }
                    },
                    legend: {
                        show: false
                    }
                });

            // GRAPH 4
            $.plot($("#pie_chart_4"), data, {
                    series: {
                        pie: {
                            show: true,
                            radius: 1,
                            label: {
                                show: true,
                                radius: 3 / 4,
                                formatter: function (label, series) {
                                    return '<div style="font-size:8pt;text-align:center;padding:2px;color:white;">' + label + '<br/>' + Math.round(series.percent) + '%</div>';
                                },
                                background: {
                                    opacity: 0.5,
                                    color: '#000'
                                }
                            }
                        }
                    },
                    legend: {
                        show: false
                    }
                });

            // GRAPH 5
            $.plot($("#pie_chart_5"), data, {
                    series: {
                        pie: {
                            show: true,
                            radius: 3 / 4,
                            label: {
                                show: true,
                                radius: 3 / 4,
                                formatter: function (label, series) {
                                    return '<div style="font-size:8pt;text-align:center;padding:2px;color:white;">' + label + '<br/>' + Math.round(series.percent) + '%</div>';
                                },
                                background: {
                                    opacity: 0.5,
                                    color: '#000'
                                }
                            }
                        }
                    },
                    legend: {
                        show: false
                    }
                });

            // GRAPH 6
            $.plot($("#pie_chart_6"), data, {
                    series: {
                        pie: {
                            show: true,
                            radius: 1,
                            label: {
                                show: true,
                                radius: 2 / 3,
                                formatter: function (label, series) {
                                    return '<div style="font-size:8pt;text-align:center;padding:2px;color:white;">' + label + '<br/>' + Math.round(series.percent) + '%</div>';
                                },
                                threshold: 0.1
                            }
                        }
                    },
                    legend: {
                        show: false
                    }
                });

            // GRAPH 7
            $.plot($("#pie_chart_7"), data, {
                    series: {
                        pie: {
                            show: true,
                            combine: {
                                color: '#999',
                                threshold: 0.1
                            }
                        }
                    },
                    legend: {
                        show: false
                    }
                });

            // GRAPH 8
            $.plot($("#pie_chart_8"), data, {
                    series: {
                        pie: {
                            show: true,
                            radius: 300,
                            label: {
                                show: true,
                                formatter: function (label, series) {
                                    return '<div style="font-size:8pt;text-align:center;padding:2px;color:white;">' + label + '<br/>' + Math.round(series.percent) + '%</div>';
                                },
                                threshold: 0.1
                            }
                        }
                    },
                    legend: {
                        show: false
                    }
                });

            // GRAPH 9
            $.plot($("#pie_chart_9"), data, {
                    series: {
                        pie: {
                            show: true,
                            radius: 1,
                            tilt: 0.5,
                            label: {
                                show: true,
                                radius: 1,
                                formatter: function (label, series) {
                                    return '<div style="font-size:8pt;text-align:center;padding:2px;color:white;">' + label + '<br/>' + Math.round(series.percent) + '%</div>';
                                },
                                background: {
                                    opacity: 0.8
                                }
                            },
                            combine: {
                                color: '#999',
                                threshold: 0.1
                            }
                        }
                    },
                    legend: {
                        show: false
                    }
                });

            // DONUT
            $.plot($("#donut"), data, {
                    series: {
                        pie: {
                            innerRadius: 0.5,
                            show: true
                        }
                    }
                });

            // INTERACTIVE
            $.plot($("#interactive"), data, {
                    series: {
                        pie: {
                            show: true
                        }
                    },
                    grid: {
                        hoverable: true,
                        clickable: true
                    }
                });
            $("#interactive").bind("plothover", pieHover);
            $("#interactive").bind("plotclick", pieClick);

            function pieHover(event, pos, obj) {
            if (!obj)
                    return;
                percent = parseFloat(obj.series.percent).toFixed(2);
                $("#hover").html('<span style="font-weight: bold; color: ' + obj.series.color + '">' + obj.series.label + ' (' + percent + '%)</span>');
            }

            function pieClick(event, pos, obj) {
                if (!obj)
                    return;
                percent = parseFloat(obj.series.percent).toFixed(2);
                alert('' + obj.series.label + ': ' + percent + '%');
            }

        }
        
    };

}();