<?php
// For outputing a news ticker
// Sourced from: https://github.com/ocrdu/rss-scrolling-news-ticker/blob/master/feedbouncer.php
$rss_feed = "http://rss.cnn.com/rss/cnn_topstories.rss";
?>

<!DOCTYPE HTML> 
<html lang="en">
  <head>
    <title>News Ticker for Fourwinds Display</title>
    <meta charset="UTF-8"> 

    <style>
    #newswindow_wrap { position: absolute; left: 0; top: 0; background-image: url(/images/CNN.png); background-repeat: no-repeat; background-color: #75263E; background-size: 160px; background-position: 20px; width: 100%; height: 100px; padding-left:210px; }
    #newswindow { color: #fff; width: 100%; height: 100%; border: 0; overflow: hidden; white-space: nowrap;  font-family: sans-serif; font-size: 50px; line-height: 100px; }
    #newswindow a { color:#fff; text-decoration: none; outline: none; padding-right:100px; }
    </style>


  </head>
  <body onload="start('<?php echo $rss_feed ?>', 'newswindow');">
    <div id="newswindow_wrap">
        <div id="newswindow"></div>
    </div>
  </body>
</html>

<script>

var scrollPosition;
var scrollBoxWidth;
var win;

function start(feed, windowID) {
  scrollPosition = 0;
  win = document.getElementById(windowID);
  scrollBoxWidth = win.style.width.substr(0, win.style.width.indexOf("px"));
  getNews(feed);
  setInterval(function() {getNews(feed);}, 60000);
  scrollNews();
}

function getNews(feed) {
  var newsFeedRequest = new XMLHttpRequest();
  newsFeedRequest.open("GET", "feedbouncer.php?feed=" + feed, true);
  newsFeedRequest.onreadystatechange = processNewsFeed;
  newsFeedRequest.send(null);

  function processNewsFeed() {
    var items;
    var title;
    var url;
    var stream = "";
    if (newsFeedRequest.readyState == 4 && newsFeedRequest.status == 200) {
      items = newsFeedRequest.responseXML.getElementsByTagName("item");
      for (var i=0; i<items.length; i++) {
        title = items[i].getElementsByTagName("title")[0].childNodes[0].nodeValue;
        url = items[i].getElementsByTagName("link")[0].childNodes[0].nodeValue;
        stream += "<a href='" + url + "' target='_blank'>" + title + "</a> " + "   ";
      }
      win.innerHTML = stream + stream; //Trick 1
    }
  }
}

function scrollNews() {
  var interval = setInterval("moveNews()", 10);
  win.onmouseover = function() {clearInterval(interval);};
  win.onmouseout = function() {interval = setInterval('moveNews();', 10);};
}

function moveNews() {
  win.scrollLeft += 1;
  if (scrollPosition == win.scrollLeft) {
    win.scrollLeft = (win.scrollLeft - scrollBoxWidth)/2 - 1; //Trick 2
  }
  scrollPosition = win.scrollLeft;
}

</script>
