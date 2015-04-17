
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Search Engine</title>

        <!--Adding the d3 javaascript-->
        <script type="text/javascript" src="d3.min.js"></script>
        <!--Adding the stylesheet for jquery-->
        <link rel="stylesheet" href="http://code.jquery.com/ui/1.11.1/themes/smoothness/jquery-ui.css">
        <script src="http://code.jquery.com/jquery-1.10.2.js"></script>
        <script src="http://code.jquery.com/ui/1.11.1/jquery-ui.js"></script>

        <style type="text/css">
            rect {
                -moz-transition: all 0.3s;
                -o-transition: all 0.3s;
                -webkit-transition: all 0.3s;
                transition: all 0.3s;
            }
            /*hover function*/
            rect:hover {
                fill: orange;
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

            #drugButton {
                font-size: 100px;
            }

            #diseaseButton {
                font-size: 100px;
            }

        </style>
    </head>
    <body>
    <div id="tooltip" class="hidden">
        <p><strong></strong></p>
        <p><span id="value">20</span></p>
    </div> 
        
    <div>
        <h1>Most Common Diagnoses after Diagnosis of MS</h1>
        <script type="text/javascript"> 
            var dL = [
                <?php
                    $dXList = array();
                    ini_set('auto_detect_line_endings',TRUE);
                    $handle = fopen('msReportData_commonDiseases.txt','r') or die("Unable to open file!"); 
                    if($handle){
                        while ( ($data = fgetcsv($handle, 1000) ) !== FALSE ) {
                            $x = explode(chr(9), $data[0]);
                            $code = $x[0];
                            $drugName = $x[1];
                            $numOfPatients = $x[2];

                            $dX = array(
                                $code,
                                $drugName,
                                $numOfPatients
                            );

                            array_push($dXList, $dX);
                        }
                    } else {
                        echo "Error reading File";
                    }
                    fclose($handle);
                    echo json_encode($dXList);
                ?>
            ];

            /*
            var splitList = function(array) {
                var len = array.length
                var arr1 = array.slice(0, len/3)
                var arr2 = array.slice(len/3-1, 2*(len/3))
                var arr3 = array.slice(2*(len/3)-1, len)
                var arrays = [arr1, arr2, arr3]
            }
            splitList(rxL[0]);
            */

            var barHeight = 20
            var barwidth = 10
            var buffer = 5
            var h = barHeight*dL[0].length
            var textBuffer = 450
            var w = 1000


            var svg = d3.select("body")
            .append("svg")
            .attr({
                width: w+textBuffer,
                height: h,
            });

            var maxWidthDrugs = d3.max(dL[0], function(d) {
                if (!isNaN(parseInt(d[2]))){
                    return parseInt(d[2]); // Number of patients
                }
            });

            var maxWidthDiseases = d3.max(dL[0], function(d) {
                if (!isNaN(parseInt(d[2]))){
                    return parseInt(d[2]); // Number of patients
                }
            });

            var maxWidth = Math.max(maxWidthDrugs, maxWidthDiseases);

            var yScale = d3.scale.linear()
                .domain([0, 40])
                .range([0, h]);

            var xScale = d3.scale.linear()
                .domain([0, maxWidth])
                .range([buffer, w]);

            //Function to make Bars
            var makeBars = function() { 
                svg.selectAll("rect")
                    .data(dL[0])
                    .enter()
                    .append("rect")
                    .attr({
                        width: function(d) { 
                            if(!isNaN(parseInt(d[2]))){
                                console.log(parseInt(d[2])); 
                                console.log(xScale(parseInt(d[2]))); 
                                return (xScale(parseInt(d[2]))); 
                            }
                        },
                        height: barHeight-buffer,
                        fill: function(d){
                            return "rgb(10, 150, " + (Math.floor((d[2]/2) * 150)) + ")";
                        },
                        y: function(d, j) {return (yScale(j))}, // Adjust input for proper spacing
                        x: textBuffer
                    })

                    //Adding the mouseOver function - Hover to highlight
                    .on("mouseover", function(d) {
                        var xPosition = parseFloat(d3.select(this).attr("x"))+parseFloat(d3.select(this).attr("width"));
                        var yPosition = parseFloat(d3.select(this).attr("y"))+100;

                        console.log(xPosition);

                        d3.select("#tooltip")
                            .style("left", xPosition + "px")
                            .style("top", yPosition + "px")
                            .select("#value")
                            .text(d[2]);

                        d3.select("#tooltip").classed("hidden", false);
                    })

                    .on("mouseout", function() {
                        d3.select("#tooltip").classed("hidden", true);
                    })
            }

            //Function to makeLabels
            var makeLabels = function() {
                svg.selectAll("text")
                    .data(dL[0])
                    .enter()
                    .append("text")
                    .text(function(d) {
                        if (d[1] == ""){
                            return d[0]
                        }
                        else{return d[1]}
                    })
                    .attr({
                        y: function(d, j) {
                            if (d[1] == ""){
                                return yScale(j+1.75)
                            }
                            else{return yScale(j+0.75)}
                        },
                        x: function(d) {
                            if (d[1] == ""){
                                return textBuffer
                            }
                            else{ return buffer+10}
                        },
                        "font-size": 20,
                        fill: "black" 
                    })
            }

            makeBars();
            makeLabels();
        </script>
        </div>
        
        <div>
            <h1>Most Prescribed Drugs after Diagnosis of MS</h1>
            <script type="text/javascript"> 
                var rxL = [
                    <?php
                        $dXList = array();
                        ini_set('auto_detect_line_endings',TRUE);
                        $handle = fopen('msReportData_commonDX.txt','r') or die("Unable to open file!"); 
                        if($handle){
                            while ( ($data = fgetcsv($handle, 1000) ) !== FALSE ) {
                                $x = explode(chr(9), $data[0]);
                                $code = $x[0];
                                $drugName = $x[1];
                                $numOfPatients = $x[2];

                                $dX = array(
                                    $code,
                                    $drugName,
                                    $numOfPatients
                                );

                                array_push($dXList, $dX);
                            }
                        } else {
                            echo "Error reading File";
                        }
                        fclose($handle);
                        echo json_encode($dXList);
                    ?>
                ];

                var barHeight = 20
                var barwidth = 10
                var buffer = 5
                var h = barHeight*rxL[0].length
                var textBuffer = 450
                var w = 1000

                var svg = d3.select("body")
                .append("svg")
                .attr({
                    width: w+textBuffer,
                    height: h,
                });

                var maxWidthDrugs = d3.max(rxL[0], function(d) {
                    if (!isNaN(parseInt(d[2]))){
                        return parseInt(d[2]); // Number of patients
                    }
                });

                var maxWidthDiseases = d3.max(rxL[0], function(d) {
                    if (!isNaN(parseInt(d[2]))){
                        return parseInt(d[2]); // Number of patients
                    }
                });

                var maxWidth = Math.max(maxWidthDrugs, maxWidthDiseases);

                var yScale = d3.scale.linear()
                .domain([0, 40])
                .range([0, h]);

                var xScale = d3.scale.linear()
                .domain([0, maxWidth])
                .range([buffer, w]);

                //Function to make Bars
                var makeBars = function() { 
                    svg.selectAll("rect")
                    .data(rxL[0])
                    .enter()
                    .append("rect")
                    .attr({
                        width: function(d) { 
                            if(!isNaN(parseInt(d[2]))){
                                console.log(parseInt(d[2])); 
                                console.log(xScale(parseInt(d[2]))); 
                                return (xScale(parseInt(d[2]))); 
                            }
                        },
                        height: barHeight-buffer,
                        fill: function(d){
                            return "rgb(10, 150, " + (Math.floor((d[2]/2) * 150)) + ")";
                        },
                        y: function(d, j) {return (yScale(j))}, // Adjust input for proper spacing
                        x: textBuffer
                    })

                    //Adding the mouseOver function - Hover to highlight
                    .on("mouseover", function(d) {
                        var xPosition = parseFloat(d3.select(this).attr("x"))+parseFloat(d3.select(this).attr("width"));
                        var yPosition = parseFloat(d3.select(this).attr("y"))+h+220;

                        d3.select("#tooltip")
                        .style("left", xPosition + "px")
                        .style("top", yPosition + "px")
                        .select("#value")
                        .text(d[2]);

                        d3.select("#tooltip").classed("hidden", false);
                    })

                    .on("mouseout", function() {
                        d3.select("#tooltip").classed("hidden", true);
                    })
                }
                //Function to makeLabels
                var makeLabels = function() {
                    svg.selectAll("text")
                    .data(rxL[0])
                    .enter()
                    .append("text")
                    .text(function(d) {
                        if (d[1] == ""){
                            return d[0]
                        }
                        else{return d[1]}
                    })
                    .attr({
                        y: function(d, j) {
                            if (d[1] == ""){
                                return yScale(j+1.75)
                            }
                            else{return yScale(j+0.75)}
                        },
                        x: function(d) {
                            if (d[1] == ""){
                                return textBuffer
                            }
                            else{ return buffer+10}
                        },
                        "font-size": 20,
                        fill: "black" 
                    })
                }
                makeBars();
                makeLabels();
            </script>
        </div>
    </body>