<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Search Engine</title>

<!--Adding the d3 javaascript-->
<script type="text/javascript" src="d3.min.js"></script>
<!--Adding the stylesheet for jquery-->
<link rel="stylesheet" href="http://code.jquery.com/ui/1.11.1/themes/smoothness/jquery-ui.css">
<style type="text/css">
    /*Modify Rectangles for tooltip - small overlays over data*/
    rect {
        -moz-transition: all 0.3s;
        -o-transition: all 0.3s;
        -webkit-transition: all 0.3s;
        transition: all 0.3s;
    }
    /*hover function*/
    rect:hover {
        fill: blue;
    }
    #tooltip {
        position: absolute;
        width: 40px;
        height: 20px;
        padding: 10px;
        background-color: white;
        -webkit-border-radius: 10px;
        -moz-border-radius: 10px;
        border-radius: 10px;
        -webkit-box-shadow: 4px 4px 10px rgba(0, 0, 0, 0.4);
        -moz-box-shadow: 4px 4px 10px rgba(0, 0, 0, 0.4);
        box-shadow: 4px 4px 10px rgba(0, 0, 0, 0.4);
        pointer-events: none;
    }

    #tooltip.hidden {
        display: none;
    }

    #tooltip p {
        margin: 0;
        font-family: georgia;
        font-size: 30px;
        line-height: 20px;
    }

    body {
        background-image:url(gray_jean/gray_jean.png)
    }

    h1 {
        position: relative;
        left: 1%;
        top: 2%;
        font-size: 50px;
    }

    .axis path,

    .axis line {
        fill: none;
        stroke: black;
        shape-rendering: crispEdges;
    }

    .axis text {
        font-family: georgia;
        font-size: 15px;
    }
</style>

</head>
<body>
    <h1>Year of Diagnosis for Multiple Sclerosis</h1>
    <div id="tooltip" class="hidden">
        <p><strong></strong></p>
        <p><span id="value">20</span></p>
    </div>

    <script type="text/javascript"> 
    var pL = [
        <?php
            $dXList = array();
            ini_set('auto_detect_line_endings',TRUE);
            $handle = fopen('msReportData_DXbyYear.txt','r') or die("Unable to open file!"); 
            if($handle){
                while ( ($data = fgetcsv($handle, 1000) ) !== FALSE ) {
                    $x = explode(chr(9), $data[0]);
                    $Year = $x[0];
                    $NumDiagnoses = $x[1];

                    $dXYear = array(
                        $Year,
                        $NumDiagnoses
                    );

                    array_push($dXList, $dXYear);
                }
            } else {
                echo "Error reading File";
            }
            fclose($handle);
            echo json_encode($dXList);
        ?>
    ];
    //console.log(pL);

    //Define Variables
    var buffer = 50;
    var barWidth = 60;
    var barBuffer = 5;
    var labelBuffer = 10;
    var w = (barWidth+barBuffer)*pL[0].length; // Width to include every entry
    var h = 550; 

    //Defining maxHeight - vertical limit of the graph
    var maxHeight = d3.max(pL[0], function(d) {
        return parseInt(d[1]); // Number of patients
    });
    var maxYear = d3.max(pL[0], function(d) {
        return parseInt(d[0]);
    });
    var minYear = d3.min(pL[0], function(d) {
        return parseInt(d[0]);
    });
    console.log("minYear: " + minYear + "; maxYear: " + maxYear);
        
    var yScale = d3.scale.linear()
        .domain([0, maxHeight])
        .range([0, h]);

    var yAxisScale = d3.scale.linear()
        .domain([0, maxHeight])
        .range([h, 0]);
        
    var xScale = d3.scale.linear()
        .domain([minYear, maxYear])
        .range([buffer, w]);

    var svg = d3.select("body")
        .append("svg")
        .attr({
            width: w,
            height: h,
        });

    //Function to make Bars
    var makeBars = function() {
    svg.selectAll("rect")
        .data(pL[0])
        .enter()
        .append("rect")
        .attr({
            width: barWidth, 
            height: function(d) {
                if(!isNaN(parseInt(d[1]))) {return (yScale(parseInt(d[1])))}
            },
            fill: function(d){
                return "rgb(10, 150, 100)";
            },
            y: function(d){ if(!isNaN(parseInt(d[1]))) {return h-(yScale(parseInt(d[1])))}},
            x: function(d){ if(!isNaN(parseInt(d[0]))) {
                return xScale(parseInt(d[0]))}},
        })
        //Adding the mouseOver function - Hover to highlight
        .on("mouseover", function(d) {
            var xPosition = parseFloat(d3.select(this).attr("x"));
            var yPosition = parseFloat(d3.select(this).attr("y"));

            d3.select("#tooltip")
                .style("left", xPosition + "px")
                .style("top", yPosition+70 + "px")
                .select("#value")
                .text(d[1]); //function(d) {return d[0]}),

            d3.select("#tooltip").classed("hidden", false);
        })
        .on("mouseout", function() {
             d3.select("#tooltip").classed("hidden", true);
        })
    }

    //Function to makeLabels
    var makeLabels = function() {
        svg.selectAll("text")
        .data(pL[0])
        .enter()
        .append("text")
        .text(function(d) {return d[0]})
        .attr({
            y: function(d){ if(!isNaN(parseInt(d[1]))) {return h-labelBuffer}}, 
            x: function(d){ if(!isNaN(parseInt(d[0]))) {return xScale(parseInt(d[0])) + labelBuffer}},
            "font-size": 20,
            fill: "white" 
        })
    }
    makeBars();
    makeLabels();

    //Interesting Note - this needs to go after making the bars/labels or else the labels will not appear
    var yAxis = d3.svg.axis()
        .scale(yAxisScale)
        .orient("left")
        .ticks(13);

    svg.append("g")
        .attr("class", "axis")
        .attr("transform", "translate(" + (buffer-10) + ",0)")
        .call(yAxis);
    </script>
</body>