<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Multiple Sclerosis Drug Information Page</title>
        <!--Adding the d3 javaascript-->
        <script type="text/javascript" src="d3.min.js"></script>
        <!--Adding the stylesheet for jquery-->
        <link rel="stylesheet" href="http://code.jquery.com/ui/1.11.1/themes/smoothness/jquery-ui.css">
        <link rel="stylesheet" type="text/css" href="msStyle.css">
    </head>
    <body>
        <div>
            
<!-- I) YEAR OF DIAGNOSIS FOR MULTIPLE SCLEROSIS -->
            <h2 align="left">Diseases of Interes: Multiple Sclerosis</h2>
            <h2 align="left">Total Number of Patients: 4286</h2>
            <h2 align="left">Columbia University Medical Center</h2>           
            
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
                
                //Define Variables
                var buffer = 50;
                var barWidth = 50;
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
                
                var yScale = d3.scale.linear()
                    .domain([0, maxHeight])
                    .range([0, h-buffer]);
                
                var yAxisScale = d3.scale.linear()
                    .domain([0, maxHeight])
                    .range([h-buffer, 0]);
                
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
                            return "rgb(10, 100, 100)";
                        },
                        y: function(d){ if(!isNaN(parseInt(d[1]))) {return h-buffer-(yScale(parseInt(d[1])))}},
                        x: function(d){ if(!isNaN(parseInt(d[0]))) {
                            return xScale(parseInt(d[0]))}},
                    })
                    //Adding the mouseOver function - Hover to highlight
                    .on("mouseover", function(d) {
                        var xPosition = parseFloat(d3.select(this).attr("x"));
                        var yPosition = parseFloat(d3.select(this).attr("y")) + 135; // Correcting Value
                        
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
                        fill: "black" 
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
        </div>
        
<!-- II) AGE OF PATIENTS AT DIAGNOSIS OF MS -->

        <div>
            <h1>Age of Patients at Diagnosis of MS</h1>
            <div id="tooltip" class="hidden">
                <p><strong></strong></p>
                <p><span id="value">20</span></p>
            </div>

            <script type="text/javascript"> 
                var pL = [
                    <?php
                        $patientList = array();
                        ini_set('auto_detect_line_endings',TRUE);
                        $handle = fopen('msReportData_DXbyAge.txt','r') or die("Unable to open file!"); 
                        if($handle){
                            while ( ($data = fgetcsv($handle, 1000) ) !== FALSE ) {
                                $x = explode(chr(9), $data[0]);
                                $Age = $x[0];
                                $NumPatients = $x[1];

                                $patient = array(
                                    $Age,
                                    $NumPatients
                                );

                                array_push($patientList, $patient);
                            }
                        } else {
                            echo "Error reading File";
                        }
                        fclose($handle);     
                        echo json_encode($patientList);
                    ?>
                ];

                //Define Variables
                var buffer = 50;
                var labelBuffer = 20;
                var barWidth = 20;
                var barBuffer = 5
                var w = (barWidth+barBuffer)*pL[0].length; // Width to include every entry
                var h = 550; 


                //Defining maxHeight - vertical limit of the graph
                var maxHeight = d3.max(pL[0], function(d) {
                    return parseInt(d[1]); // Number of patients
                });

                var maxWidth = d3.max(pL[0], function(d) {
                    return parseInt(d[0]);
                });

                var yScale = d3.scale.linear()
                .domain([0, maxHeight])
                .range([0, h-labelBuffer]);

                var yAxisScale = d3.scale.linear()
                .domain([0, maxHeight])
                .range([h-labelBuffer, 0]);

                var xScale = d3.scale.linear()
                .domain([0, maxWidth])
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
                            return (yScale(parseInt(d[1])))
                        },
                        fill: function(d){
                            return "rgb(10, 100, 100)";
                        },
                        y: function(d){return h-labelBuffer-(yScale(parseInt(d[1])))},
                        x: function(d,j) {return xScale(j)} 
                    })
                    //Adding the mouseOver function - Hover to highlight
                    .on("mouseover", function(d) {
                        var xPosition = parseFloat(d3.select(this).attr("x"));
                        var yPosition = parseFloat(d3.select(this).attr("y"))+h*2+85; // Correcting Value

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
                    .text(function(d) {if (parseInt(d[0])%10 == 0) return d[0]})
                    .attr({
                        x: function(d,j) {return xScale(j) + barBuffer}, 
                        y: h-barBuffer,//function(d,j) {return h-(yScale(parseInt(d[1])))},
                        "font-size": 15,
                        fill: "black" 
                    })
                }
                makeBars();
                makeLabels();

                var yAxis = d3.svg.axis()
                .scale(yAxisScale)
                .orient("left")
                .ticks(13);

                svg.append("g")
                .attr("class", "axis")
                .attr("transform", "translate(" + (buffer-10) + ",0)")
                .call(yAxis);
            </script>   
        </div>

<!-- III) OBSERVATION PERIODS FOR MULTIPLE SCLEROSIS-->  
        <div>
            <h1>Observation Periods for Multiple Sclerosis</h1>
            <div id="tooltip" class="hidden">
                <p><strong></strong></p>
                <p><span id="value">20</span></p>
            </div>

            <script type="text/javascript"> 
                var pL = [
                    <?php
                        $periodList = array();
                        ini_set('auto_detect_line_endings',TRUE);
                        $handle = fopen('msReportData_ObsYears.txt','r') or die("Unable to open file!"); 
                        if($handle){
                            while ( ($data = fgetcsv($handle, 1000) ) !== FALSE ) {
                                $x = explode(chr(9), $data[0]);
                                $ObsYears = $x[0];
                                $NumPatients = $x[1];

                                $period = array(
                                    $ObsYears,
                                    $NumPatients
                                );

                                array_push($periodList, $period);
                            }
                        } else {
                            echo "Error reading File";
                        }
                        fclose($handle);
                        echo json_encode($periodList);
                    ?>
                ];

                //Define Variables
                var buffer = 70;
                var barWidth = 50;
                var barBuffer = 5;
                var labelBuffer = 20;
                var w = (barWidth+barBuffer)*pL[0].length; // Width to include every entry
                var h = 550; 

                //Defining maxHeight - vertical limit of the graph
                var maxHeight = d3.max(pL[0], function(d) {
                    return parseInt(d[1]); // Number of patients
                });
                var maxWidth = d3.max(pL[0], function(d) {
                    return parseInt(d[0]);
                });

                var yScale = d3.scale.linear()
                .domain([0, maxHeight])
                .range([0, h-labelBuffer]);

                var yAxisScale = d3.scale.linear()
                .domain([0, maxHeight])
                .range([h-labelBuffer, 0]);

                var xScale = d3.scale.linear()
                .domain([0, maxWidth])
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
                            return "rgb(10, 100, 100)";
                        },
                        y: function(d){ if(!isNaN(parseInt(d[1]))) {return h-labelBuffer-(yScale(parseInt(d[1])))}},
                        x: function(d){ 
                            if(!isNaN(parseInt(d[0]))) {
                                return xScale(parseInt(d[0]))
                            }
                            else if(d[0] == "< 1"){
                                return xScale(0) 
                            }
                        },
                    })
                    //Adding the mouseOver function - Hover to highlight
                    .on("mouseover", function(d) {
                        var xPosition = parseFloat(d3.select(this).attr("x"));
                        var yPosition = parseFloat(d3.select(this).attr("y"))+4*h+40; // Correcting Value

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
                    .text(function(d) {
                        return d[0]
                    })
                    .attr({
                        y: function(d){ if(!isNaN(parseInt(d[1]))) {return h-5}}, 
                        x: function(d){ 
                            if(!isNaN(parseInt(d[0]))) {
                                return xScale(parseInt(d[0])) + labelBuffer
                            }
                            else if(d[0] == "< 1"){
                                return xScale(0) + labelBuffer-5
                            }
                        },
                        "font-size": 20,
                        fill: "black" 
                    })
                }
                makeBars();
                makeLabels();

                var yAxis = d3.svg.axis()
                    .scale(yAxisScale)
                    .orient("left")
                    .ticks(13);

                svg.append("g")
                    .attr("class", "axis")
                    .attr("transform", "translate(" + (buffer-10) + ",0)")
                    .call(yAxis);
            </script>            
        </div>
        
<!-- IV) MOST COMMON DIAGNOSES AFTER DIAGNOSIS OF MS -->
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
                
                var w = 2000
                var barHeight = 30
                var buffer = 5
                var barBuffer = 20
                var h = barHeight*dL[0].length/3
                var textBuffer = 400

                var sectionSize = (w-buffer)/3 - 100

                var svg = d3.select("body")
                .append("svg")
                .attr({
                    width: w+textBuffer,
                    height: h,
                });

                var maxWidth = d3.max(dL[0], function(d) {
                    if (!isNaN(parseInt(d[2]))){
                        return parseInt(d[2]); // Number of patients
                    }
                });

                var yScale = d3.scale.linear()
                    .domain([0, 12])
                    .range([0, h]);

                var xScale = d3.scale.linear()
                    .domain([0, maxWidth])
                    .range([buffer, buffer+sectionSize-textBuffer]);

                //Function to make Bars
                var makeBars = function() { 
                    svg.selectAll("rect")
                    .data(dL[0])
                    .enter()
                    .append("rect")
                    .attr({
                        width: function(d) { 
                            if(!isNaN(parseInt(d[2]))){
                                return (xScale(parseInt(d[2]))); 
                            }
                        },
                        height: barHeight-buffer,
                        fill: function(d){
                            return "rgb(10, 50, 250)";
                        },
                        y: function(d, j) {return (yScale(j%12))}, // Adjust input for proper spacing
                        x: function(d) {
                            if (dL[0].indexOf(d) < 12){
                                return textBuffer;                    
                            }
                            else if (dL[0].indexOf(d) < 24){
                                return sectionSize + textBuffer;
                            }
                            else{return sectionSize*2 + textBuffer} 
                        } 
                    })

                    //Adding the mouseOver function - Hover to highlight
                    .on("mouseover", function(d) {
                        var xPosition = parseFloat(d3.select(this).attr("x"));//+parseFloat(d3.select(this).attr("width"));
                        var yPosition = parseFloat(d3.select(this).attr("y"))+6*h + 85; // Correcting Value

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
                        else if (d[1] == "disease name"){
                            return ""
                        }
                        else{return d[1]}
                    })
                    .attr({
                        y: function(d, j) {
                            if (d[1] == ""){
                                return yScale(1)
                            }
                            else{return yScale(j%12+0.75)}
                        },
                        x: function(d) {
                            if (d[1] == ""){
                                if (dL[0].indexOf(d) == 0 ){
                                    return buffer;                           
                                }
                                else if (dL[0].indexOf(d) == 12){
                                    return sectionSize;
                                }
                                else{ return sectionSize*2} 
                            } 
                            else{ 
                                if (dL[0].indexOf(d) < 12){
                                    return buffer;                    
                                }
                                else if (dL[0].indexOf(d) < 24){
                                    return sectionSize + buffer;
                                }
                                else{ return sectionSize*2} 
                            } 
                        },
                        "font-size": 20,
                        fill: "black",
                        
                    })
                }

                makeBars();
                makeLabels();
                

            </script>
        </div>

<!-- V) MOST PRESCRIBED DRUGS AFTER DIAGNOSIS OF MS --> 
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

                var w = 2000
                var barHeight = 30
                var buffer = 5
                var barBuffer = 20
                var h = barHeight*rxL[0].length/3
                var textBuffer = 400

                var sectionSize = (w-buffer)/3 - 100

                var svg = d3.select("body")
                .append("svg")
                .attr({
                    width: w+textBuffer,
                    height: h,
                });

                var maxWidth = d3.max(rxL[0], function(d) {
                    if (!isNaN(parseInt(d[2]))){
                        return parseInt(d[2]); // Number of patients
                    }
                });

                var yScale = d3.scale.linear()
                .domain([0, 12])
                .range([0, h]);

                var xScale = d3.scale.linear()
                .domain([0, maxWidth])
                .range([buffer, buffer+sectionSize-textBuffer]);

                //Function to make Bars
                var makeBars = function() { 
                    svg.selectAll("rect")
                    .data(rxL[0])
                    .enter()
                    .append("rect")
                    .attr({
                        width: function(d) { 
                            if(!isNaN(parseInt(d[2]))){
                                return (xScale(parseInt(d[2]))); 
                            }
                        },
                        height: barHeight-buffer,
                        fill: function(d){
                            return "rgb(250, 20, 50)";
                        },
                        y: function(d, j) {return (yScale(j%12))}, // Adjust input for proper spacing
                        x: function(d) {
                            if (rxL[0].indexOf(d) < 12){
                                return textBuffer;                    
                            }
                            else if (rxL[0].indexOf(d) < 24){
                                return sectionSize + textBuffer;
                            }
                            else{return sectionSize*2 + textBuffer} 
                        } 
                    })

                    //Adding the mouseOver function - Hover to highlight
                    .on("mouseover", function(d) {
                        var xPosition = parseFloat(d3.select(this).attr("x"));
                        var yPosition = parseFloat(d3.select(this).attr("y"))+7*h + 210; // Correcting Value

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
                        else if (d[1] == "drug name"){
                            return ""
                        }
                        else{return d[1]}
                    })
                    .attr({
                        y: function(d, j) {
                            if (d[1] == ""){
                                return yScale(1)
                            }
                            else{return yScale(j%12+0.75)}
                        },
                        x: function(d) {
                            if (d[1] == ""){
                                if (rxL[0].indexOf(d) == 0 ){
                                    return buffer;                           
                                }
                                else if (rxL[0].indexOf(d) == 12){
                                    return sectionSize;
                                }
                                else{ return sectionSize*2} 
                            } 
                            else{ 
                                if (rxL[0].indexOf(d) < 12){
                                    return buffer;                    
                                }
                                else if (rxL[0].indexOf(d) < 24){
                                    return sectionSize + buffer;
                                }
                                else{ return sectionSize*2} 
                            } 
                        },
                        "font-size": 20,
                        fill: "black",
                    })
                }  
                makeBars()
                makeLabels()
            </script>
        </div>
    </body>