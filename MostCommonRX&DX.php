
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
        <table id="myTable" border="1">
            <tr>
                <td></td>
                <td>Top 10 Most Prescribed Drugs after Diagnosis of MS</td>
                <td></td>
                <td></td>
                <td>Top 10 Most Prescribed Drugs within 1 year after Diagnosis of MS</td>
                <td></td>
                <td></td>
                <td>Top 10 Most Prescribed Drugs within 1 year after Diagnosis of MS</td>
                <td></td>
            </tr>
            <tr>
                <td>code</td>
                <td>drug name</td>
                <td>number of patients</td>
                <td>code</td>
                <td>drug name</td>
                <td>number of patients</td>
                <td>code</td>
                <td>drug name</td>
                <td>number of patients</td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        </table>
        <form>
            <input id="drugButton" type="button" onclick="addDrugInfo()" value="Top 10 Most Common Drugs" style="width: 400px">
        </form>
        <form>
            <input id="diseaseButton" type="button" onclick="addDiseaseInfo()" value="Top 10 Most Common Diagnoses" style="width: 400px">
        </form>
        
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
               
            var rxSize = rxL[0].length/3
            var dSize = dL[0].length/3
            
            function addDrugInfo(){
                var x=document.getElementById('myTable').rows
                var header = x[0].cells
                header[1].innerHTML = rxL[0][0][0]
                header[4].innerHTML = rxL[0][1*12][0]
                header[7].innerHTML = rxL[0][2*12][0]
                for (i=1; i<rxSize; i++){
                    var row = x[i].cells;
                    for (j=0; j<3; j++){
                        //console.log(rxL[0][i][j]);
                        row[j].innerHTML = rxL[0][i][j]
                        row[j+3].innerHTML = rxL[0][i+rxSize][j]
                        row[j+6].innerHTML = rxL[0][i+(rxSize*2)][j]
                    }
                } 
            }
            
            
            function addDiseaseInfo(){
                var x=document.getElementById('myTable').rows
                var header = x[0].cells
                header[1].innerHTML = dL[0][0][0]
                header[4].innerHTML = dL[0][1*12][0]
                header[7].innerHTML = dL[0][2*12][0]
                for (i=1; i<dSize; i++){
                    var row = x[i].cells;
                    for (j=0; j<3; j++){
                        //console.log(rxL[0][i][j]);
                        row[j].innerHTML = dL[0][i][j]
                        row[j+3].innerHTML = dL[0][i+dSize][j]
                        row[j+6].innerHTML = dL[0][i+(dSize*2)][j]
                    }
                } 
            }
     
        </script>
    </body>
    

        
        
        