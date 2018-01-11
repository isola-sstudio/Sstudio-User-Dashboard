/*well, it is expected that variables containing user task data from the
 database would have been set already on this page.. located in the
 task_controller file
*/
//check that there are ongoing and probably some completed tasks
if (tasksGraphTimeline) {
  //now create an array to hold the labels on the x axis
  var xAxisLabel = [0];
  //before we continue, let's have an array for months
  var monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun","Jul", "Aug", "Sep",
  "Oct", "Nov", "Dec"];

  for (var i = 0; i < tasksGraphTimeline.length; i++) {
    dateFormat = new Date(tasksGraphTimeline[i]+"Z");
    dateString = monthNames[dateFormat.getMonth()] + ' ' + dateFormat.getDate() + ', ' + dateFormat.getFullYear();
    xAxisLabel.push(dateString);
  }

  // now create total ongoing tasks line
  // console.log(totalOngoingTasksGraph);
  var totalOngoingTasksGraphSeries = [0];
  var totalCountHere = 0;
  var i = 0;
  for(key in totalOngoingTasksGraph){
    totalCountHere = parseInt(totalOngoingTasksGraph[tasksGraphTimeline[i]]) + totalCountHere;
     totalOngoingTasksGraphSeries.push({meta:totalCountHere +' Ongoing Tasks', value: totalCountHere});
     i++;
  }

  // now create total ongoing tasks line
  // console.log(totalTasksGraph);
  var totalTasksGraphSeries = [0];
  var totalCountHere = 0;
  var i = 0;
  for(key in totalTasksGraph){
    totalCountHere = parseInt(totalTasksGraph[tasksGraphTimeline[i]]) + totalCountHere;
     totalTasksGraphSeries.push({meta:totalCountHere +' Tasks in Total', value: totalCountHere});
     i++;
     // console.log(totalCountHere);
  }

}else {
    //let us have a flat line graph
    // the x axis
    xAxisLabel = [];

    //before we continue, let's have an array for months
    var monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun","Jul", "Aug", "Sep",
    "Oct", "Nov", "Dec"];

    for (var i = 0; i < 3; i++) {
      dateFormat = new Date();
      dateFormat.setDate(dateFormat.getDate() - i);
      dateString = monthNames[dateFormat.getMonth()] + ' ' + dateFormat.getDate() + ', ' + dateFormat.getFullYear();
      xAxisLabel.push(dateString);
    }

    totalOngoingTasksGraphSeries = [{meta: '0 Task Ongoing', value: 0},
                                    {meta: '0 Task Ongoing', value: 0},
                                    {meta: '0 Task Ongoing', value: 0},
                                    {meta: '0 Task Ongoing', value: 60}
                                  ];
    totalTasksGraphSeries = [{meta: '0 Tasks in Total', value: 0},
                              {meta: '0 Tasks in Total', value: 0},
                              {meta: '0 Tasks in Total', value: 0},
                              {meta: '0 Tasks in Total', value: 60}
                            ];
  }


$(document).ready(function () {
    "use strict";
    // toat popup js
    // $.toast({
    //     heading: 'Welcome to Ample admin',
    //     text: 'Use the predefined ones, or specify a custom position object.',
    //     position: 'top-right',
    //     loaderBg: '#fff',
    //     icon: 'warning',
    //     hideAfter: 3500,
    //     stack: 6
    // })


    //ct-visits
    // Create a simple line chart
    var data = {
        // A labels array that can contain any sort of values
        labels: xAxisLabel,
        // Our series array that contains series objects or in this case series data arrays
        series: [totalTasksGraphSeries, totalOngoingTasksGraphSeries
            // ,
            // [
            //     {meta: 'Task Created', value: 0},
            //     {meta: 'Updated Task Description', value: 15},
            //     {meta: 'description', value: 30},
            //     {meta: 'description', value: 60}
            // ]
        ]
    };

    // We are setting a few options for our chart and override the defaults
    var options = {
        showPoint: true,
        lineSmooth: Chartist.Interpolation.simple({
            divisor: 2
        }),
        fullWidth: true,
        stretch: false,
        axisY: {
            offset: 60,
            labelInterpolationFnc: function(value) {
                return value;
            }
        },
        axisX: {
            offset: 40,
            labelInterpolationFnc: function(value) {
                return value;
            }
        },
        showArea: true,
        plugins: [
            Chartist.plugins.tooltip(),
            Chartist.plugins.ctAxisTitle({

                axisY: {
                    axisTitle: '# of tasks',
                    axisClass: 'ct-axis-title',
                    offset: {
                        x: 0,
                        y: 24
                    },
                    textAnchor: 'middle',
                    flipTitle: true
                },
                axisX: {
                    axisTitle: 'Date',
                    axisClass: 'ct-axis-title',
                    offset: {
                        x: 0,
                        y: 40
                    },
                    textAnchor: 'middle'
                }
            })
        ]
    };

// In the global name space Chartist we call the Line function to initialize a line chart. As a first parameter we pass in a selector where we would like to get our chart created. Second parameter is the actual data object and as a third parameter we pass in our options
    var chart = new Chartist.Line('#ct-visits', data, options);
    // Let's put a sequence number aside so we can use it in the event callbacks
    var seq = 0,
        delays = 50,
        durations = 500;

// Once the chart is fully created we reset the sequence
    chart.on('created', function() {
        seq = 0;
    });

// On each drawn element by Chartist we use the Chartist.Svg API to trigger SMIL animations
    chart.on('draw', function(data) {
        seq++;

        if(data.type === 'line') {
            // If the drawn element is a line we do a simple opacity fade in. This could also be achieved using CSS3 animations.
            data.element.animate({
                opacity: {
                    // The delay when we like to start the animation
                    begin: seq * delays + 1000,
                    // Duration of the animation
                    dur: durations,
                    // The value where the animation should start
                    from: 0,
                    // The value where it should end
                    to: 1
                }
            });
        } else if(data.type === 'label' && data.axis === 'x') {
            data.element.animate({
                y: {
                    begin: seq * delays,
                    dur: durations,
                    from: data.y + 100,
                    to: data.y,
                    // We can specify an easing function from Chartist.Svg.Easing
                    easing: 'easeOutQuart'
                }
            });
        } else if(data.type === 'label' && data.axis === 'y') {
            data.element.animate({
                x: {
                    begin: seq * delays,
                    dur: durations,
                    from: data.x - 100,
                    to: data.x,
                    easing: 'easeOutQuart'
                }
            });
        } else if(data.type === 'point') {
            data.element.animate({
                x1: {
                    begin: seq * delays,
                    dur: durations,
                    from: data.x - 10,
                    to: data.x,
                    easing: 'easeOutQuart'
                },
                x2: {
                    begin: seq * delays,
                    dur: durations,
                    from: data.x - 10,
                    to: data.x,
                    easing: 'easeOutQuart'
                },
                opacity: {
                    begin: seq * delays,
                    dur: durations,
                    from: 0,
                    to: 1,
                    easing: 'easeOutQuart'
                }
            });
        } else if(data.type === 'grid') {
            // Using data.axis we get x or y which we can use to construct our animation definition objects
            var pos1Animation = {
                begin: seq * delays,
                dur: durations,
                from: data[data.axis.units.pos + '1'] - 30,
                to: data[data.axis.units.pos + '1'],
                easing: 'easeOutQuart'
            };

            var pos2Animation = {
                begin: seq * delays,
                dur: durations,
                from: data[data.axis.units.pos + '2'] - 100,
                to: data[data.axis.units.pos + '2'],
                easing: 'easeOutQuart'
            };

            var animations = {};
            animations[data.axis.units.pos + '1'] = pos1Animation;
            animations[data.axis.units.pos + '2'] = pos2Animation;
            animations['opacity'] = {
                begin: seq * delays,
                dur: durations,
                from: 0,
                to: 1,
                easing: 'easeOutQuart'
            };

            data.element.animate(animations);
        }
    });

// For the sake of the example we update the chart every time it's created with a delay of 10 seconds
//     chart.on('created', function() {
//         if(window.__exampleAnimateTimeout) {
//             clearTimeout(window.__exampleAnimateTimeout);
//             window.__exampleAnimateTimeout = null;
//         }
//         window.__exampleAnimateTimeout = setTimeout(chart.update.bind(chart), 12000);
//     });
    //    new Chartist.Line('#ct-visits', {
    //        labels: ['2008', '2009', '2010', '2011', '2012', '2013', '2014', '2015'],
    //        series: [
    //   [5, 2, 7, 4, 5, 3, 5, 4]
    //   , [2, 5, 2, 6, 2, 5, 2, 4]
    // ]
    //    }, {
    //        top: 0,
    //        low: 1,
    //        showPoint: true,
    //        fullWidth: true,
    //        plugins: [
    //   Chartist.plugins.tooltip()
    // ],
    //        axisY: {
    //            labelInterpolationFnc: function (value) {
    //                return (value / 1) + 'k';
    //            }
    //        },
    //        showArea: true
    //    });
    // counter
    $(".counter").counterUp({
        delay: 100,
        time: 1200
    });

    var sparklineLogin = function () {
        $('#sparklinedash').sparkline([0, 5, 6, 10, 9, 12, 4, 9], {
            type: 'bar',
            height: '30',
            barWidth: '4',
            resize: true,
            barSpacing: '5',
            barColor: '#7ace4c'
        });
        $('#sparklinedash2').sparkline([0, 5, 6, 10, 9, 12, 4, 9], {
            type: 'bar',
            height: '30',
            barWidth: '4',
            resize: true,
            barSpacing: '5',
            barColor: '#7460ee'
        });
        $('#sparklinedash3').sparkline([0, 5, 6, 10, 9, 12, 4, 9], {
            type: 'bar',
            height: '30',
            barWidth: '4',
            resize: true,
            barSpacing: '5',
            barColor: '#11a0f8'
        });
        $('#sparklinedash4').sparkline([0, 5, 6, 10, 9, 12, 4, 9], {
            type: 'bar',
            height: '30',
            barWidth: '4',
            resize: true,
            barSpacing: '5',
            barColor: '#f33155'
        });
    }
    var sparkResize;
    $(window).on("resize", function (e) {
        clearTimeout(sparkResize);
        sparkResize = setTimeout(sparklineLogin, 500);
    });
    sparklineLogin();
});
