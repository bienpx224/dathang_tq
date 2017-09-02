<script>
function chart2() {
	var views_1 = [
		<?php if($views_1){echo DelStr($views_1);};//删除最后一个,号?>
	];
	var views_2 = [
		<?php if($views_2){echo DelStr($views_2);};//删除最后一个,号?>
	];

	var plot = $.plot($("#chart_2"), [{
				data: views_1,
				label: "数量"
			}, {
				data: views_2,
				label: "<?=$views_2_name?>"
			}
		], {
			series: {
				lines: {
					show: true,
					lineWidth: 2,
					fill: true,
					fillColor: {
						colors: [{
								opacity: 0.05
							}, {
								opacity: 0.01
							}
						]
					}
				},
				points: {
					show: true
				},
				shadowSize: 2
			},
			grid: {
				hoverable: true,
				clickable: true,
				tickColor: "#eee",
				borderWidth: 0
			},
			colors: ["#37b7f3", "#d12610", "#52e136"],
			xaxis: {
				ticks: 11,
				tickDecimals: 0
			},
			yaxis: {
				ticks: 11,
				tickDecimals: 0
			}
		});


	function showTooltip(x, y, contents) {
		$('<div id="tooltip">' + contents + '</div>').css({
				position: 'absolute',
				display: 'none',
				top: y + 5,
				left: x + 15,
				border: '1px solid #333',
				padding: '4px',
				color: '#fff',
				'border-radius': '3px',
				'background-color': '#333',
				opacity: 0.80
			}).appendTo("body").fadeIn(200);
	}

	var previousPoint = null;
	$("#chart_2").bind("plothover", function (event, pos, item) {
		$("#x").text(pos.x.toFixed(2));
		$("#y").text(pos.y.toFixed(2));

		if (item) {
			if (previousPoint != item.dataIndex) {
				previousPoint = item.dataIndex;

				$("#tooltip").remove();
				var x = item.datapoint[0].toFixed(0),//小数点
					y = item.datapoint[1].toFixed(0);

				showTooltip(item.pageX, item.pageY,y+" ("+x+"<?=$charts_timename?>"+item.series.label+")");//鼠标移上显示
			}
		} else {
			$("#tooltip").remove();
			previousPoint = null;
		}
	});
}

</script>

<script src="/bootstrap/plugins/flot/jquery.flot.js"></script> 
<script src="/bootstrap/scripts/charts.js"></script> 

<script>
  $(function(){       
	 Charts.initCharts();
	 chart2();//必须,charts.js文件已修改,搜索://已关闭
  });
</script> 
