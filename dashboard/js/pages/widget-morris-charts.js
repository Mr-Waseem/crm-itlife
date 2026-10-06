//[widget morris charts Javascript]


$(function() {
	"use strict";	
    if($('#morris_1').length > 0)
		// Area Chart
		Morris.Area({
			element: 'morris_1',
			data: [{
				period: '2010 Q1',
				iphone: 2566,
				ipad: null,
				iMac: 2866
			}, {
				period: '2010 Q2',
				iphone: 2458,
				ipad: 2658,
				iMac: 2452
			}, {
				period: '2010 Q3',	
				iphone: 4859,
				ipad: 1952,
				iMac: 2658
			}, {
				period: '2010 Q4',
				iphone: 3489,
				ipad: 3698,
				iMac: 5986
			}, {
				period: '2011 Q1',
				iphone: 6589,
				ipad: 1698,
				iMac: 2548
			}, {
				period: '2011 Q2',
				iphone: 5986,
				ipad: 4587,
				iMac: 1589
			}, {
				period: '2011 Q3',
				iphone: 4589,
				ipad: 3547,
				iMac: 1548
			}, {
				period: '2011 Q4',
				iphone: 15698,
				ipad: 8591,
				iMac: 4589
			}, {
				period: '2012 Q1',
				iphone: 12548,
				ipad: 4526,
				iMac: 2563
			}, {
				period: '2012 Q2',
				iphone: 9658,
				ipad: 8457,
				iMac: 1485
			}],
			xkey: 'period',
			ykeys: ['iphone', 'ipad', 'iMac'],
			labels: ['iPhone', 'iPad', 'iMac'],
			pointSize: 0,
			lineWidth:0,
			fillOpacity: 1,
			pointStrokeColors:['#666EE8', '#FF9149', '#28D094'],
			behaveLikeLine: true,
			grid: false,
			hideHover: 'auto',
			lineColors: ['#666EE8', '#FF9149', '#28D094'],
			resize: true,
			redraw: true,
			smooth: true,
			gridTextColor:'#878787',
		});

    if($('#morris_2').length > 0) {
		// Donut Chart
		Morris.Donut({
			element: 'morris_2',
			data: [{
				label: "Download Sales",
				value: 451
			}, {
				label: "In-Store Sales",
				value: 125
			}, {
				label: "Mail-Order Sales",
				value: 235
			}],
			colors: ['#666EE8', '#28D094', '#FF4961'],
			resize: true,
			labelColor: '#878787',
		});
	}	

    if($('#morris_3').length > 0)
		// Line Chart
		Morris.Line({
			// ID of the element in which to draw the chart.
			element: 'morris_3',
			// Chart data records -- each entry in this array corresponds to a point on
			// the chart.
			data: [{
				d: '2012-01',
				visits: 985
			}, {
				d: '2012-02',
				visits: 548
			}, {
				d: '2012-03',
				visits: 521
			}, {
				d: '2012-04',
				visits: 652
			}, {
				d: '2012-05',
				visits: 412
			}, {
				d: '2012-06',
				visits: 956
			}, {
				d: '2012-07',
				visits: 745
			}, {
				d: '2012-08',
				visits: 1548
			}, {
				d: '2012-09',
				visits: 1563
			}, {
				d: '2012-10',
				visits: 1254
			}, {
				d: '2012-11',
				visits: 458
			}, {
				d: '2012-12',
				visits: 845
			}, {
				d: '2013-01',
				visits: 758
			}, {
				d: '2013-02',
				visits: 659
			}, {
				d: '2013-03',
				visits: 845
			}, {
				d: '2013-04',
				visits: 745
			}, {
				d: '2013-05',
				visits: 1258
			}, {
				d: '2013-06',
				visits: 1458
			}, {
				d: '2013-07',
				visits: 1258
			}, {
				d: '2013-08',
				visits: 1358
			}, {
				d: '2013-09',
				visits: 1548
			}, {
				d: '2013-10',
				visits: 1547
			}, {
				d: '2013-11',
				visits: 1254
			}, {
				d: '2013-12',
				visits: 1235
			}, {
				d: '2014-01',
				visits: 1458
			}, {
				d: '2014-02',
				visits: 1236
			}, {
				d: '2014-03',
				visits: 1458
			}, {
				d: '2014-04',
				visits: 1485
			}, {
				d: '2014-05',
				visits: 1254
			}, {
				d: '2014-06',
				visits: 1698
			}, {
				d: '2014-07',
				visits: 1952
			}, ],
			// The name of the data record attribute that contains x-visitss.
			xkey: 'd',
			// A list of names of data record attributes that contain y-visitss.
			ykeys: ['visits'],
			// Labels for the ykeys -- will be displayed when you hover over the
			// chart.
			labels: ['Visits'],
			// Disables line smoothing
			pointSize: 1,
			pointStrokeColors:['#666EE8'],
			behaveLikeLine: true,
			grid:false,
			gridTextColor:'#878787',
			lineWidth: 2,
			smooth: true,
			hideHover: 'auto',
			lineColors: ['#666EE8'],
			resize: true,
		});

	if($('#morris_4').length > 0)
	   // Bar Chart
		Morris.Bar({
			element: 'morris_4',
			data: [{
				device: 'iPhone 6',
				sale: 852
			}, {
				device: 'iPhone 6s',
				sale: 963
			}, {
				device: 'iPhone 7',
				sale: 1111
			}, {
				device: 'iPhone 7s',
				sale: 1596
			}, {
				device: 'iPhone 8',
				sale: 2015
			}, {
				device: 'iPhone x',
				sale: 2358
			}],
			xkey: 'device',
			ykeys: ['sale'],
			labels: ['Sale'],
			barRatio: 0.4,
			xLabelAngle: 35,
			pointSize: 1,
			barOpacity: 1,
			pointStrokeColors:['#666EE8'],
			behaveLikeLine: true,
			grid: false,
			gridTextColor:'#878787',
			hideHover: 'auto',
			barColors: ['#666EE8'],
			resize: true,
		});
	
	if($('#morris_5').length > 0)
		Morris.Line({
        element: 'morris_5',
        data: [{
            period: '2012',
            iphone: 45,
            ipad: 57,
            imac: 85
        }, {
            period: '2013',
            iphone: 159,
            ipad: 123,
            imac: 75
        }, {
            period: '2014',
            iphone: 125,
            ipad: 145,
            imac: 45
        }, {
            period: '2015',
            iphone: 78,
            ipad: 195,
            imac: 125
        }, {
            period: '2016',
            iphone: 241,
            ipad: 125,
            imac: 159
        }, {
            period: '2017',
            iphone: 125,
            ipad: 125,
            imac: 90
        },
         {
            period: '2018',
            iphone: 298,
            ipad: 198,
            imac: 185
        }],
        xkey: 'period',
        ykeys: ['iphone', 'ipad', 'imac'],
        labels: ['iPhone', 'iPad', 'iMac'],
        pointSize: 2,
        fillOpacity: 0,
		lineWidth:2,
		pointStrokeColors:['#666EE8', '#28D094', '#FF4961'],
		behaveLikeLine: true,
		grid: false,
		hideHover: 'auto',
		lineColors: ['#666EE8', '#28D094', '#FF4961'],
		resize: true,
		gridTextColor:'#878787',
        
    });
	
	if($('#morris_6').length > 0)
		// Morris bar chart
		Morris.Bar({
			element: 'morris_6',
			data: [{
				y: '2012',
				a: 125,
				b: 89,
				c: 25
			}, {
				y: '2013',
				a: 125,
				b: 85,
				c: 52
			}, {
				y: '2014',
				a: 52,
				b: 65,
				c: 35
			}, {
				y: '2015',
				a: 75,
				b: 68,
				c: 36
			}, {
				y: '2016',
				a: 78,
				b: 96,
				c: 45
			}, {
				y: '2017',
				a: 89,
				b: 96,
				c: 47
			}, {
				y: '2018',
				a: 125,
				b: 75,
				c: 56
			}],
			xkey: 'y',
			ykeys: ['a', 'b', 'c'],
			labels: ['A', 'B', 'C'],
			barColors:['#666EE8', '#28D094', '#FF4961'],
			barOpacity:1,
			hideHover: 'auto',
			grid: false,
			resize: true,
			gridTextColor:'#878787',
		});

});