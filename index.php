<?php

function map($value, $low1, $high1, $low2, $high2) {
    return ($value / ($high1 - $low1)) * ($high2 - $low2) + $low2;
}

	
	if(isset($_GET['off']))
		{
		    shell_exec("/usr/bin/gpio -g mode 12 out");
         	shell_exec("/usr/bin/gpio -g write 12 0");
        }
        else if(isset($_GET['on']))
        {
			shell_exec("/usr/bin/gpio -g mode 12 out");
            shell_exec("/usr/bin/gpio -g write 12 1");
        }
		else if(isset($_GET['dim']))
        {
            $val = (int)map($_GET['dim'],0,100,0,1023);
			shell_exec("/usr/bin/gpio -g mode 12 pwm");
            shell_exec("/usr/bin/gpio -g pwm 12 ".$val);
        }
        else if(isset($_GET['blink']))
        {
            if (empty($_GET['blink'])) {
				$bl = 5;
			}
            else {
                $bl=$_GET['blink'];
            }
			shell_exec("/usr/bin/gpio -g mode 12 out");
			for ($x = 0; $x <= $bl; $x++) {
                shell_exec("/usr/bin/gpio -g toggle 12"); 
				sleep(1);
			}  
        }
        else if(isset($_GET['stop']))
        {
			exit;
		}
?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no, minimal-ui, viewport-fit=cover">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <title>IoT Pertama Saya</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/framework7/4.3.0/css/framework7.bundle.min.css">
</head>
<body class="color-theme-red">
    <div id="app">
      <div class="statusbar"></div>
      <div class="view view-main">
        <div data-name="home" class="page">
          <div class="navbar color-blue">
            <div class="navbar-inner">
              <div class="title">IoT Pertamaku</div>
            </div>
          </div>

          <div class="page-content">
			<div class="block-title">Kontrol Led</div>
				<div class="list simple-list">
  					<ul>
    					<li>
      						<span>LED</span>
      						<label class="toggle toggle-init color-green">
       							<input type="checkbox">
        						<span class="toggle-icon"></span>
      						</label>
    					</li>
  					</ul>
				</div>
				<div class="block-title">Brightness</div>
					<div class="list simple-list">
  						<ul>
    						<li>
      							<div class="item-cell width-auto flex-shrink-0">Redup</div>
      								<div class="item-cell flex-shrink-3">
        							<div class="range-slider range-slider-init color-orange" data-label="true">
          								<input type="range" min="0" max="100" step="1" value="100">
        							</div>
     							</div>
      							<div class="item-cell width-auto flex-shrink-0">Terang</div>
    						</li>
  						</ul>
					</div>
				<div class="block-title">Blink LED</div>
				<div class="block">
  				<p class="row">
    				<button id="blink" class="col button button-large button-raised button-fill color-blue">Blink</button>
    
  				</p>
				</div>

          </div>
        </div>
      </div>
    </div>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/framework7/4.3.0/js/framework7.bundle.js"></script>
<script>
var app = new Framework7({
  root: '#app',
  name: 'My App',
  theme : 'ios',
  id: 'com.myapp.test',
  panel: {
    swipe: 'left',
  },
});
var toggle = app.toggle.create({
  el: '.toggle',
  on: {
    change: function () {
		if (toggle.checked){
			app.request.get("?on");
		}
		else {
			app.request.get("?off");
		}
    }
  }
});
var range = app.range.create({
  el: '.range-slider',
  on: {
    changed: function () {
      app.request.get("?dim="+range.value);
    }
  }
});
var $$ = Dom7;
$$('#blink').on('click', function (e) {
    app.request.get("?blink");
});
$$('#stop').on('click', function (e) {
    app.request.get("?stop");
	app.request.get("?stop");
});
</script>
</body>
</html>
