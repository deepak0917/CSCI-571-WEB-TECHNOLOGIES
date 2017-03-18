<!doctype html>
<html>

<head>

	<title>Forecast Search</title>
	<meta charset="utf-8" />
	<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
	<meta name="description" content="Deepak CSCI 571 HW 6" />
    
    <script>
        
        function clearForm(){
            document.getElementById("myForm").reset();
            document.getElementById("output").innerHTML = "";
            
            document.getElementById("streetAddress").value="";
            document.getElementById("city").value="";
            var select= document.getElementById("state");
            select.selectedIndex=0;
            document.getElementById("fahrenheit").checked=true; 
            document.getElementById("celsius").checked=false; 
        }
        
        function validateForm(){
            var x = document.getElementById("streetAddress").value;
            var re = /^\s*$/;
            if( x == null || x == "" || re.test(x)){
                alert("Please enter Street Address");
                return false;
            }
            var x = document.getElementById("city").value;
            if( x == null || x == "" || re.test(x)){
                alert("Please enter value for City");
                return false;
            }
            var x = document.getElementById("state").value;
            if( x == "Select your state..."){
                alert("Please enter value for State");
                return false;
            }
        }
        

        var states=["Select your state...", "Alabama", "Alaska", "Arizona", "Arkansas", "California", "Colorado", "Connecticut", "Delaware", "District Of Columbia", "Florida", "Georgia", "Hawaii", "Idaho", "Illinois", "Indiana", "Iowa", "Kansas", "Kentucky", "Louisiana", "Maine", "Maryland", "Massachusetts", "Michigan", "Minnesota", "Mississippi", "Missouri", "Montana", "Nebraska", "Nevada", "New Hampshire", "New Jersey", "New Mexico", "New York", "North Carolina", "North Dakota", "Ohio", "Oklahoma", "Oregon", "Pennsylvania", "Rhode Island", "South Carolina", "South Dakota", "Tennessee", "Texas", "Utah", "Vermont", "Virginia", "Washington", "West Virginia", "Wisconsin", "Wyoming"];

        var symbols=["Select your state...", "AL", "AK", "AZ", "AR", "CA", "CO", "CT", "DE", "DC", "FL", "GA", "HI", "ID", "IL", "IN", "IA", "KS", "KY", "LA", "ME", "MD", "MA", "MI", "MN", "MS", "MO", "MT", "NE", "NV", "NH", "NJ", "NM", "NY", "NC", "ND", "OH", "OK", "OR", "PA", "RI", "SC", "SD", "TN", "TX", "UT", "VT", "VA", "WA", "WV", "WI", "WY"];

        function initializeSelect(){
            var select = document.getElementById('state');
                for(var i = 0; i < states.length; i++) {
                    var opt = document.createElement('option');
                    opt.innerHTML = states[i];
                    opt.value = symbols[i];
                    select.appendChild(opt);
                }
        }
        
    

    </script>
    
    <style>
        
        td{
            text-align:left;
            padding-left:50px;
            padding-right:50px;
        }
        
        td.center{
            text-align:center;
            padding:0px;


        }
        
        #resultTable {
            width:500px; 
            height:400px;
            padding-bottom:20px;
            padding-top:20px;
        }
        
    </style>
	
</head>

<body>
            
    <h1 align=center>Forecast Search</h1>
    
    <table align=center border=1.5px rules=none style="padding:4px;">
        
        <form name="myForm" action="forecast.php" onsubmit="return validateForm();" method="POST" id="myForm">
            
        <tr>
            <td>Street Address:*</td>
            <td><input type="text" id="streetAddress" name="StreetAddress" value="<?php echo isset($_POST["StreetAddress"]) ? $_POST["StreetAddress"] : "" ?>" /><br></td>
        </tr>

        <tr>
            <td>City:*</td>
            <td><input type="text" id="city" name="City" value="<?php echo isset($_POST["City"]) ? $_POST["City"] : "" ?>" /><br></td>                </tr>
        <tr>
            <td>State:*</td>
            <td><select id="state" name="State"></select> <br></td>
        </tr>
            
        <script type="text/javascript">
            initializeSelect();
        </script>
        
        <?php if(isset($_POST["submit"])): ?>
            
             <?php
    
                 $stateSelected=$_POST["State"];
                 echo "<script>
                            var select = document.getElementById('state');
                            for(var i = 0; i < select.length; i++) {
                                var opt = select.options[i];
                                if(opt.value == \"".$stateSelected."\"){
                                opt.setAttribute(\"selected\", \"selected\");
                                }
                            }       
                       </script>"; 
             ?>

            <tr>
                <td>Degree:*
                </td><td><input type="radio" name="unit" value="Fahrenheit" id="fahrenheit" <?php if($_POST["unit"] == "Fahrenheit")echo "checked"; ?> />Fahrenheit<input type="radio" name="unit" value="Degree" id="celsius" <?php if($_POST["unit"] == "Degree")echo "checked"; ?> >Degree </input> <br></td>
            </tr>

        <?php else: ?>

            <tr>
                <td>Degree:*</td>
                <td>
                    <input type="radio" name="unit" value="Fahrenheit"  id="fahrenheit" checked />Fahrenheit
                    <input type="radio" name="unit" value="Degree" id="celsius" />Degree <br>
                </td>
            </tr>

        <?php endif; ?>

        <tr>
            <td></td>
            <td>
                <input type="submit" name="submit" value="Search" > </input>
                &nbsp;  
                <input type="button" name="clear" value="Clear" onclick="clearForm()"></input>
            </td>
        </tr>

        <tr>
            <td><i>*- Mandatory fields.</i></td>
        </tr>

        <tr>
            <td colspan=2 class="center"><br/><a href="http://forecast.io/"><u>Powered by Forecast.io</u></a></td>
        </tr>
    
        </form> 

    </table>
    
    
    <?php if(isset($_POST["submit"])): ?>
    
    <div id="output">
    <?php 

        $urlA = rawurlencode("https://maps.googleapis.com/maps/api/geocode/xml?");
        $urlB = urlencode("address=".$_POST["StreetAddress"]. "," .  $_POST["City"] . "," . $_POST["State"] . "&key=AIzaSyBTsfwGZIxBYDk-SpEta0CLquAVlUkNRPg");
        $google_url = $urlA . $urlB;                  
                          
        $xml = simplexml_load_file($google_url) or die("url not loading"); 
        //echo "googleurl ->" . $google_url . "<br>"; 
        //print_r($xml);

        if($xml->result){
            $lat = (string) $xml->result->geometry->location->lat;
            $long = (string) $xml->result->geometry->location->lng; 
        }
        else{
            echo '<script language="javascript">';
            echo 'alert("Please enter a valid address")';
            echo '</script>';
            return;
        }
            
        //echo "lat". $lat;
        //echo "long" . $long;

        if($_POST["unit"] == "Fahrenheit"){
            $uv = "us";
            $unitOfTemperature = "F";
            $unitOfWindSpeed = "mph";
            $unitOfVisibilty = "mi";
        }
        else{
            $uv = "si";
            $unitOfTemperature = "C";
            $unitOfWindSpeed="m/s";
            $unitOfVisibilty ="km";

        }

        $forecastAPIKEY = "f936f6c35c37265a692691ccaacc8219";
        $urla = "https://api.forecast.io/forecast/$forecastAPIKEY/$lat,$long?";
            
        $urlb = "units=$uv&exclude=flags";        
        $forecast_url = $urla . $urlb;
        //echo "<br><br>URL: " . $forecast_url;
        $json = file_get_contents($forecast_url);

        //echo "<br><br>JSON: ";
        //var_dump($json);
        $obj = json_decode($json);
        
        echo "<br><br>";
        echo "<table border=1.5px rules=none align=center id=\"resultTable\">";
        
        $timeZone=$obj->{"timezone"};
        date_default_timezone_set($timeZone);

        $summary = $obj->currently->{"summary"};
        echo "<tr><td colspan=2 class=\"center\"><h2 style=\"padding:0; margin:0;\">$summary</h2></td><tr>";

        $temperature = $obj->currently->{"temperature"};
        echo "<tr><td colspan=2 class=\"center\"><h2 style=\"padding:0; margin:0;\">".round($temperature)."&deg; $unitOfTemperature</h2></td><tr>";

        $icon = $obj->currently->{"icon"};
        switch($icon){
            case "clear-day": $image = "clear";break;
            case "clear-night": $image = "clear_night";break;
            case "rain": $image = "rain"; break;
            case "snow": $image = "snow"; break;
            case "sleet": $image = "sleet"; break;
            case "wind": $image = "wind"; break;
            case "fog": $image = "fog"; break;
            case "cloudy": $image = "cloudy"; break;
            case "partly-cloudy-day": $image = "cloud_day"; break;
            case "partly-cloudy-night": $image = "cloud_night"; break;
        }

        echo "<tr><td colspan=2 class=\"center\"><img src=\"http://cs-server.usc.edu:45678/hw/hw6/images/$image.png\" alt=\"$icon\" title=\"$icon\" width=\"80px\" height=\"80px\" /></td></tr>";
        
        $precipitation = $obj->currently->{"precipIntensity"};
        if($uv = "si"){
            $precipitation=$precipitation/25.4;
        }

        
        if(0 <= $precipitation && $precipitation < 0.002)
        {
            $temp="None";
        }
        else  if(0.002 <= $precipitation && $precipitation < 0.017)
        {
            $temp="Very Light";
        }
        
        else  if(0.017 <= $precipitation && $precipitation < 0.1){
            $temp="Light";
        }
        else  if(0.1 <= $precipitation && $precipitation < 0.4){
            $temp="Moderate";
        }
        else  if(0.4 <= $precipitation){
            $temp="Heavy";
        }

        echo "<tr><td>Precipitation: </td><td>$temp</td><tr>";
        
        $chanceOfRain = intval($obj->currently->{"precipProbability"})*100 . "%";
        echo "<tr><td>Chance Of Rain: </td><td>$chanceOfRain</td><tr>";

        $windSpeed = $obj->currently->{"windSpeed"};
        echo "<tr><td>WindSpeed: </td><td>".round($windSpeed). " $unitOfWindSpeed</td><tr>";

        $dewPoint = $obj->currently->{"dewPoint"};
        echo "<tr><td>Dew Point: </td><td>".round($dewPoint)."&deg; $unitOfTemperature</td><tr>";
        
        $humidity = ($obj->currently->{"humidity"})*100 . "%";
        echo "<tr><td>Humidity: </td><td>$humidity</td><tr>";

        $visibility = $obj->currently->{"visibility"};
        echo "<tr><td>Visibility: </td><td>".round($visibility). " $unitOfVisibilty</td><tr>";

        $sunriseTime=$obj->{"daily"}->{"data"}[0]->{"sunriseTime"};
        echo "<tr><td>Sunrise: </td><td>".date("h:i A",$sunriseTime)."<br></td></tr>";
        
        $sunsetTime=$obj->{"daily"}->{"data"}[0]->{"sunsetTime"};
        echo "<tr><td>Sunset: </td><td>".date("h:i A",$sunsetTime)."<br></td></tr>";



        echo "</table>";
        
    ?>
    
    <?php endif; ?>

    </div>
	<noscript> 
	
</body>

</html>