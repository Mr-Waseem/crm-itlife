//[Dashboard Javascript]

//Project:	SoftMaterial admin - Responsive Admin Template
//Primary use:   Used only for the main dashboard (index.html)

$(function () {

  'use strict';

  // Make the dashboard widgets sortable Using jquery UI
  $('.connectedSortable').sortable({
    placeholder         : 'sort-highlight',
    connectWith         : '.connectedSortable',
    handle              : '.box-header, .nav-tabs',
    forcePlaceholderSize: true,
    zIndex              : 999999
  });
  $('.connectedSortable .box-header, .connectedSortable .nav-tabs-custom').css('cursor', 'move');

// jQuery UI sortable for the todo list
  $('.todo-list').sortable({
    placeholder         : 'sort-highlight',
    handle              : '.handle',
    forcePlaceholderSize: true,
    zIndex              : 999999
  });	
	
/* The todo list plugin */
  $('.todo-list').todoList({
    onCheck  : function () {
      window.console.log($(this), 'The element has been checked');
    },
    onUnCheck: function () {
      window.console.log($(this), 'The element has been unchecked');
    }
  });	
	
/* countnm */	
	$('.countnm').each(function () {
		$(this).prop('Counter',0).animate({
			Counter: $(this).text()
		}, {
			duration: 5000,
			easing: 'swing',
			step: function (now) {
				$(this).text(Math.ceil(now));
			}
		});
	});	
	

// AREA CHART
	
	Morris.Bar({
        element: 'morris-area-chart1',
        data: [{
            period: '2012',
            OPD: 85,
            ICU: 95,
            
        }, {
            period: '2013',
            OPD: 157,
            ICU: 123,
            
        }, {
            period: '2014',
            OPD: 89,
            ICU: 100,
            
        }, {
            period: '2015',
            OPD: 125,
            ICU: 200,
            
        }, {
            period: '2016',
            OPD: 180,
            ICU: 152,
            
        }, {
            period: '2017',
            OPD: 142,
            ICU: 100,
            
        },
         {
            period: '2018',
            OPD: 244,
            ICU: 182,
           
        }],
        xkey: 'period',
        ykeys: ['OPD', 'ICU'],
        labels: ['OPD', 'ICU'],
        pointSize: 0,
       
        pointStrokeColors:['#666EE8', '#FF4961'],
        barColors:['#666EE8', '#FF4961'],
        behaveLikeLine: true,
        gridLineColor: '#e0e0e0',
        lineWidth: 0,
        smooth: false,
        hideHover: 'auto',
        lineColors: ['#666EE8', '#FF4961'],
        resize: true
        
    });
	
    if($('#morris_extra_line_chart').length > 0)
		Morris.Line({
        element: 'morris_extra_line_chart',
        data: [{
            period: '2012',
            opd: 50,
            operation: 80,
            lab: 90,
            medicine: 20
        }, {
            period: '2013',
            opd: 130,
            operation: 100,
            lab: 190,
            medicine: 80
        }, {
            period: '2014',
            opd: 80,
            operation: 60,
            lab: 90,
            medicine: 70
        }, {
            period: '2015',
            opd: 70,
            operation: 200,
            lab: 60,
            medicine: 140
        }, {
            period: '2016',
            opd: 180,
            operation: 150,
            lab: 80,
            medicine: 140
        }, {
            period: '2017',
            opd: 105,
            operation: 100,
            lab: 110,
            medicine: 80
        },
         {
            period: '2018',
            opd: 250,
            operation: 150,
            lab: 80,
            medicine: 200
        }],
        xkey: 'period',
        ykeys: ['opd', 'operation', 'lab', 'medicine'],
        labels: ['OPD', 'Operation', 'Lab', 'Medicine'],
        pointSize: 2,
        fillOpacity: 0,
		lineWidth:2,
		pointStrokeColors:['#666EE8', '#1E9FF2', '#FF4961', '#FF9149'],
		behaveLikeLine: true,
		grid: false,
		hideHover: 'auto',
		lineColors: ['#666EE8', '#1E9FF2', '#FF4961', '#FF9149'],
		resize: true,
		gridTextColor:'#878787',
		gridTextFamily:"Open Sans"
        
    });
	

	
}); // End of use strict

