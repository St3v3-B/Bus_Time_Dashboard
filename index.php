<?php
// Functie om het IP adres van de gebruiker te krijgen
function getUserIpAddr(){
    if(!empty($_SERVER['HTTP_CLIENT_IP'])){
        //ip from share internet
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    }elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
        //ip pass from proxy
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }else{
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}
$user_ip = getUserIpAddr(); // Sla het gebruikers IP op in een variabele

// Voorbeeld van een eenvoudige IP-check
if($user_ip == "145.144.180.44"){ // Vervang dit met het IP-adres dat je wilt checken
    $iframe_src ="https://gadgets.buienradar.nl/gadget/zoommap/?lat=51.965&lng=6.28889&overname=2&zoom=13&naam=Doetinchem&size=3&voor=1";
} else {
    $iframe_src = "https://gadgets.buienradar.nl/gadget/zoommap/?lat=51.89&lng=6.37778&overname=2&zoom=13&naam=Ulft&size=3&voor=1";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Bus Data</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        /* Je bestaande CSS hier */
        .table-container {
        border: 5px solid #E75105; /* setting border orange */
        border-radius: 25px; /* rounding corners */
        background-color: #303030; /* seting a light grey background */
        margin: 10px;
        padding: 20px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: top;
        color: #ffffff;
        font-weight: bold;
    }

    tr,td {
        border: none;
        padding: 0;
        color: #ffffff;
        align-items: center;
        font-size: 25px;
    }
        .table {
            width: 100%;
            border-collapse: collapse;
        border-spacing: 0;
        margin-bottom: 0;
        }

        #data-container {
            display: grid;
            /* Default is voor mobile, 1 kolom */
            grid-template-columns: 1fr;
            grid-gap: 15px;
            margin-top: 20px;
            padding-top: 70px;
        }

        .table tbody tr {
            min-height: 50px;
            /* Pas dit indien nodig aan */
        }

        /* Voor tablets landscape en groter */
        @media only screen and (min-width: 768px) {
            #data-container {
                /* 2 kolommen voor tablets  */
                grid-template-columns: 1fr;
                padding-top: 70px;
            }
        }

        /* Voor desktops en groter */
        @media only screen and (min-width: 1024px) {
            #data-container {
                /* 4 kolommen voor desktops */
                grid-template-columns: 1fr;
                padding-top: 70px;
            }
        }

        body {
        background-color: #282828;
        padding-bottom: 70px; /* Dit is een donkergrijstint */
        padding-top: 0px;

    }
    .table tr td {
        border: none;
    }
    .delay {
        text-decoration: underline red;
    text-decoration-thickness: 5px; /* Dikte van de onderlijn */
    text-underline-offset: 3px;
   }
   .news-ticker {
            position: fixed; /* Fixeer onderaan de pagina */
            bottom: 0;
            left: 0;
            width: 100%;
            overflow: hidden;
            background-color: #333;
            color: white;
            white-space: nowrap;
            box-sizing: border-box;
            padding-bottom: 30px; /* Voeg padding toe voor boven en beneden */
            bottom: 30px; /* Ruimte van de onderkant, aan te passen naar wens */
        }

        .news-title {
    white-space: nowrap;
    display: inline-block;
    display: inline-block;
            padding-left: 100%;
            animation: ticker 180s infinite linear;
            font-size: 40px; /* Verhoog de tekstgrootte */
            font-weight: bold;
    /* Het kan nodig zijn de animatie te fine-tunen om te passen bij de lengte van de inhoud */
}

/* Reset de animatie om beter te passen bij het verdubbelen van de content */
@keyframes ticker {
    0% { transform: translate3d(0, 0, 0); }
    100% { transform: translate3d(-100%, 0, 0); }
}
 
        
        #clock-div {
	color: white;
	float: right;
	background-color: #303030;
	border-radius: 0 0 0 25px;
	border-left: 5px solid #E75105;
	border-bottom: 5px solid #E75105;
	min-width: 475px;
   font-weight: bold;
}

#timedate {
	text-align: center;
	width: 100%;
	margin: 40px auto;
	color: #ffff;
	background-color: #303030;
	font: 50px "Segoe UI", "Frutiger", "Frutiger Linotype", "Dejavu Sans", "Helvetica Neue", Arial, sans-serif
}

#demo {
	text-align: center
}

.ow-row {
	position: relative;
	overflow: hidden;
	margin-bottom: 4%;
}

.ow-ico {
	line-height: 1.1em
}

.ow-widget {
	font-size: 16px;
	padding: 2%;
}
	.pull-left {
		float: left
	}

	.pull-right {
		float: right
	}


.ow-city-name {
	font-size: 24px
}
.ow-forecast-temp{
   font-size: 30px;

}
.ow-ico-current {
	font-size: 80px;
	width: 1.1em;

}

.ow-temp-current {
	font-size: 60px;
	margin: 0 20%;
    align-self: center;
}

.ow-current-desc {
	line-height: 2em;
    padding-top: 20px;
    align-self: center;
    font-size: 20px;
}

.ow-forecast-item {
	float: left;
	width: calc(25% - 1px);
	text-align: center;
	border-right: 1px solid white;
   font-size: 20px;
} 
	.ow-ico-forecast {
		font-size: 25px;
		position: relative;
		margin: 10px 0
	}

	.ow-forecast-temp span {
		display: inline-block;
		margin: 0 5px
	}
iframe {  
    padding-top: 110px;
    padding-left: 60px;
}
.radarr {
flex: 0;
}

    </style>
</head>
<body onload="initClock();">
    <div class="float-left" id="data-container">
        <!-- De gegevens worden hier dynamisch ingeladen via AJAX -->
    </div>
    <div id="news-ticker" class="news-ticker">
        <!-- Nieuwsberichten komen hier -->
    </div>
    <iframe id="dynamic-iframe" src="<?php echo $iframe_src; ?>"scrolling=no width=610 height=800 frameborder=no></iframe>
   <div id="clock-div">
      <div id="timedate">
         <a id="h">12</a><a>:</a>
         <a id="m">00</a><a>:</a>
         <a id="s">00</a><br>
         <a id="day">dag</a> <br>
         <a id="d">1</a>
         <a id="mon">January</a><a>,</a>
         <a id="y">0</a>
      </div>
      <section id="demo">
         <div class="ow-widget">
            <div class="ow-row">
               <span class="ow-city-name"></span>
            </div>
            <div class="ow-row">
               <div class="wi ow-ico ow-ico-current pull-left"></div>
               <div class="ow-temp-current"></div>
               <div class="ow-current-desc">
                  <div><span class="ow-pressure"></span></div>
                  <div><span class="ow-humidity"></span></div>
                  <div><span class="ow-wind"></span></div>
               </div>
            </div>
            <div class="ow-row ow-forecast">
               <div class="ow-forecast-item">
                  <div class="ow-day"></div>
                  <div class="wi ow-ico ow-ico-forecast"></div>
                  <div class="ow-forecast-temp">
                     <span class="max"></span><br>
                     <span class="min"></span>
                  </div>
               </div>
               <div class="ow-forecast-item">
                  <div class="ow-day"></div>
                  <div class="wi ow-ico ow-ico-forecast"></div>
                  <div class="ow-forecast-temp">
                     <span class="max"></span><br>
                     <span class="min"></span>
                  </div>
               </div>
               <div class="ow-forecast-item">
                  <div class="ow-day"></div>
                  <div class="wi ow-ico ow-ico-forecast"></div>
                  <div class="ow-forecast-temp">
                     <span class="max"></span><br>
                     <span class="min"></span>
                  </div>
               </div>
               <div class="ow-forecast-item">
                  <div class="ow-day"></div>
                  <div class="wi ow-ico ow-ico-forecast"></div>
                  <div class="ow-forecast-temp">
                     <span class="max"></span><br>
                     <span class="min"></span>
                  </div>
               </div>
            </div>
         </div>
      </section>
   </div>
   <br><br><br><br><br>
   <div id="radarr" class="radarr">
        <!-- Nieuwsberichten komen hier -->
    </div>
<script>
        $(document).ready(function() {
            fetchData();
            fetchNews();
            setInterval(fetchData, 10000);
            setInterval(fetchNews, 900000);
        });

                function fetchData() {
            $.ajax({
                url: '/fetch_data.php',
                method: 'GET',
                success: function(data) {
                    $('#data-container').html(data);
                }
            });
        }

        function fetchNews() {
    const rss_url = encodeURIComponent('https://feeds.nos.nl/nosnieuwsalgemeen');
    $.getJSON('https://api.rss2json.com/v1/api.json?rss_url=' + rss_url, function(data) {
        var items = data.items;
        var newsHtml = "<span class='news-title'>";
        $(items).each(function(index, item) {
            newsHtml += item.title + " &bull; ";
        });
        // Verdubbel de nieuwsitems voor een vloeiende doorlopende loop
        newsHtml += "</span><span class='news-title'>";
        $("#news-ticker").html(newsHtml);
    }).fail(function() {
        console.log("Error fetching news.");
    });
}

        // START CLOCK SCRIPT
Number.prototype.pad=function(n) {
	for (var r=this.toString(); r.length < n; r=0 + r);
	return r;
}

;

function updateClock() {
	let now=new Date();
	let sec=now.getSeconds(),
	min=now.getMinutes(),
	hou=now.getHours(),
	mo=now.getMonth(),
	dy=now.getDate(),
	day=now.getDay(),
	yr=now.getFullYear();
	let months=["Januari",
	"Februari",
	"Maart",
	"April",
	"Mei",
	"Juni",
	"Juli",
	"Augustus",
	"September",
	"Oktober",
	"November",
	"December"];
	let days=["Zondag",
	"Maandag",
	"Dinsdag",
	"Woensdag",
	"Donderdag",
	"Vrijdag",
	"Zaterdag"];
	let tags=["mon",
	"d",
	"y",
	"day",
	"h",
	"m",
	"s"],
	corr=[months[mo],
	dy,
	yr,
	days[day],
	hou.pad(2),
	min.pad(2),
	sec.pad(2)];
	for (let i=0; i < tags.length; i++) document.getElementById(tags[i]).firstChild.nodeValue=corr[i];
}

function initClock() {
	updateClock();
	window.setInterval("updateClock()", 1);
}

// END CLOCK SCRIPT

let weatherWidget = {
   settings: {
      api_key: '6bd5b850178e2134497c4b965fbaf54e',
      weather_url: 'https://api.openweathermap.org/data/2.5/weather',
      forecast_url: 'https://api.openweathermap.org/data/2.5/forecast',
      search_type: 'city_name',
      city_name: '',
      units: 'metric',
      icon_mapping: {
         '01d': 'wi-day-sunny',
         '01n': 'wi-day-sunny',
         '02d': 'wi-day-cloudy',
         '02n': 'wi-day-cloudy',
         '03d': 'wi-cloud',
         '03n': 'wi-cloud',
         '04d': 'wi-cloudy',
         '04n': 'wi-cloudy',
         '09d': 'wi-rain',
         '09n': 'wi-rain',
         '10d': 'wi-day-rain',
         '10n': 'wi-day-rain',
         '11d': 'wi-thunderstorm',
         '11n': 'wi-thunderstorm',
         '13d': 'wi-snow',
         '13n': 'wi-snow',
         '50d': 'wi-fog',
         '50n': 'wi-fog'
      }
   },
   constant: {
      dow: ['Zondag', 'Maandag', 'Dinsdag', 'Woensdag', 'Donderdag', 'Vrijdag', 'Zaterdag']
   }
};

weatherWidget.init = function (settings) {
   this.settings = Object.assign(this.settings, settings);
   Promise.all([this.getWeather(), this.getForecast()]).then((resolve) => {
      let weather = resolve[0];
      let forecast = resolve[1].list;


      document.getElementsByClassName('ow-city-name')[0].innerHTML = weather.name;
      document.getElementsByClassName('ow-temp-current')[0].innerHTML = Math.round(weather.main.temp) + '&deg;C';

      if (!!this.settings.icon_mapping[weather.weather[0].icon]) {
         let icon = this.settings.icon_mapping[weather.weather[0].icon];
         let ico_current = document.getElementsByClassName('ow-ico-current')[0];
         if (ico_current.classList) {
            ico_current.classList.add(icon);
         } else {
            ico_current.className += ' ' + icon;
         }

      }

      forecast = forecast.filter((x) => {
         return x.dt_txt.substr(0, 10) !== new Date().toJSON().slice(0, 10);
      });

      let fs = [];

      for (let f of forecast) {
         let date = f.dt_txt.substr(0, 10);
         if (!!fs[date]) {
            fs[date].temp_max = f.main.temp_max > fs[date].temp_max ? f.main.temp_max : fs[date].temp_max;
            fs[date].temp_min = f.main.temp_min < fs[date].temp_min ? f.main.temp_min : fs[date].temp_min;
            fs[date].icons.push(f.weather[0].icon);
         } else {
            fs[date] = {
               dow: this.constant.dow[new Date(date).getDay()],
               temp_max: f.main.temp_max,
               temp_min: f.main.temp_min,
               icons: [f.weather[0].icon]
            }
         }
      }

      let forecast_items = document.getElementsByClassName('ow-forecast-item');

      let counter = 0;
      for (let day in fs) {
         let icon = this.settings.icon_mapping[this.getIconWithHighestOccurence(fs[day].icons)];
         let fi = forecast_items[counter];
         fi.getElementsByClassName('max')[0].innerHTML = Math.round(fs[day].temp_max) + '&deg;C';
         fi.getElementsByClassName('min')[0].innerHTML = Math.round(fs[day].temp_min) + '&deg;C';
         fi.getElementsByClassName('ow-day')[0].innerHTML = fs[day].dow;
         let ico_current = fi.getElementsByClassName('ow-ico-forecast')[0];
         if (ico_current.classList) {
            ico_current.classList.add(icon);
         } else {
            ico_current.className += ' ' + icon;
         }
         counter++;
      }

   });
};

weatherWidget.getForecast = function () {
   let params = {
      'q': this.settings.city_name,
      'APPID': this.settings.api_key,
      'units': this.settings.units
   };

   let p = '?' + Object.keys(params)
      .map((key) => {
         return key + '=' + params[key]
      })
      .join('&');
   return this.makeRequest(this.settings.forecast_url, p);
};

weatherWidget.getWeather = function () {
   let params = {
      'q': this.settings.city_name,
      'APPID': this.settings.api_key,
      'units': this.settings.units
   };

   let p = '?' + Object.keys(params)
      .map((key) => {
         return key + '=' + params[key]
      })
      .join('&');
   return this.makeRequest(this.settings.weather_url, p);
};

weatherWidget.makeRequest = function (url, params) {
   return new Promise(function (resolve, reject) {
      let req = new XMLHttpRequest();
      req.open('GET', url + params, true);
      req.responseType = 'json';

      req.onload = function () {
         if (req.status >= 200 && req.status < 400) {
            resolve(req.response);
         } else {
            reject(Error(req.status));
         }
      };

      req.onerror = () => reject('Error occured while connecting to Weather API');
      req.send(params);
   });
};

weatherWidget.getIconWithHighestOccurence = function (a) {
   let elems = Array.prototype.slice.call(a);
   return elems.sort((a, b) =>
      elems.filter(v => v === a).length - elems.filter(v => v === b).length
   ).pop();
}

// run the widget
let widget = Object.create(weatherWidget);
widget.init({
   city_name: 'Doetinchem'
});
    </script>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>