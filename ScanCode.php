<?php
error_reporting(E_ALL & ~E_NOTICE);
session_start();

if (isset($_SESSION["id"])){
    include_once 'Dbconnect.php';
    $userId = $_SESSION["id"];
    $username = $_SESSION["username"];
} else {
    header("Location:index.php");
    die();
}
?>
<!DOCTYPE html>

<html>
    <head>
        <link href="layout.css" rel="stylesheet" type="test/css" />
        <link href="menu.css" rel="stylesheet" type="test/css" />
        <meta charset="UTF-8">
        <style type="text/css">
        body > div {
          position: relative;
          width: 320px; height: 240px;
        }
        video { position: absolute; width: 320px; height: 240px; }
        div#inner {
          position: absolute;
          margin: 0 auto;
          width: 260px; height: 180px;
          border: 30px solid rgba(64,64,64, 0.5);
          zindex: 1000;
        }
        div#redline {
          position: absolute;
          top: 370px;
          width: 320px;
          height: 2px;
          background-color: rgba(255, 0, 0, 0.3);
          zindex: 1001;
        }
      </style>
        <title></title>
    </head>
    <body>
        <div id="Holder">
            <div id="Header"><div style="float:left"><a href="Account.php"><img src="Logo.png" height="60" width="60"></a></div>
                <div style="padding-top:13px"><h1><a href="Account.php"><b>HOPIDARITY</b></a></h1></div></div>
            <div id="NavBar">
                <nav>
                    <ul>
                        <li><a href="Account.php">Profile</a></li>
                        <li><a href="#">Cupboard</a></li>
                        <li><a href="Library.php">Library</a></li>
                        <li><a href="Logout.php">Logout</a></li>
                    </ul>

                </nav>
            </div>
            <div id="Content">
                <div id="PageHeading">
                    </div>
                <div id="ContentLeft">
                     <h6>Just copy the UPC and Paste it back on the<br /> <a href="NewItem.php">Add Item Page</a> or the <a href="SearchLib.php">Search Library Page</a></h6>
                     <h6><br />If something goes wrong.<br /> you can try to scan <a href="http://bl.ocks.org/jazzido/9435670#index.html">at this link</a></h6>
                     <h6><br />Worse case scenario, you might be typing it by hand.</h6>
      </div>
                <div id="ContentRight"><div>
                 <h1>Barcode scanner</h1>
      <p>Based on <a href="https://github.com/yurydelendik/zbarjs">zbarjs</a> - Code: <a href="https://gist.github.com/jazzido/9435670">https://gist.github.com/jazzido/9435670</a></p>
      <div>
        <video autoplay></video>
        <div id="inner"></div>
        <div id="redline">
        </div>
      </div> <ul id="decoded">
      </ul>
      <canvas style="display:none;"></canvas>
      
      <script type="text/javascript">
        var video = document.querySelector('video');
        var canvas = document.querySelector('canvas');
        var ctx = canvas.getContext('2d');
        var localMediaStream = null;
        var list = document.querySelector('ul#decoded');

        var worker = new Worker('zbar-processor.js');
        worker.onmessage = function(event) {
            if (event.data.length == 0) return;
            var d = event.data[0];
            var entry = document.createElement('li');
            entry.appendChild(document.createTextNode(d[2] + ' (' + d[0] + ')'));
            list.appendChild(entry);
        };

        function snapshot() {
            if (localMediaStream === null) return;
            var k = (320 + 240) / (video.videoWidth + video.videoHeight);
            canvas.width = Math.ceil(video.videoWidth * k);
            canvas.height = Math.ceil(video.videoHeight * k);
            var ctx = canvas.getContext('2d');
            ctx.drawImage(video, 0, 0, video.videoWidth, video.videoHeight,
                          0, 0, canvas.width, canvas.height);

            var data = ctx.getImageData(0, 0, canvas.width, canvas.height);
            worker.postMessage(data);
        }

        setInterval(snapshot, 500);

        navigator.getUserMedia = navigator.getUserMedia || navigator.webkitGetUserMedia || navigator.mozGetUserMedia || navigator.msGetUserMedia;
        window.URL = window.URL || window.webkitURL || window.mozURL || window.msURL;

        if (navigator.getUserMedia) {
            navigator.getUserMedia({video: true},
                                   function(stream) { // success callback
                                       if (video.mozSrcObject !== undefined) {
                                           video.mozSrcObject = stream;
                                       } else {
                                           video.src = (window.URL && window.URL.createObjectURL(stream)) || stream;
                                       }
                                       localMediaStream = true;
                                   },
                                   function(error) {
                                       console.error(error);
                                   });
        }
        else {
        }
      </script>    
                    </div>
               
                </div>
            </div>
            <div id="Footer">
                <br /><br/>
                <h6><a href='FAQ.php'>FAQ</a></h6>
                <h6><a href='Contact.php'>Contact Us</a></h6>
                <?php 
                if($admin == 1){ echo "<h6><a href='#'>Become a Manager</a></h6>"; }
                else {echo "<h6><a href='RequestAdmin.php'>Become an Admin</a></h6>"; }
                ?>
                <h6><br />(c) 2015 All Rights Reserved</h6>
            </div>
        </div>
    </body>
</html>
