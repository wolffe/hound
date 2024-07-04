// loadCSS("/your.css");
// loadCSS("http://fonts.googleapis.com/css?family=Lobster");
// UPDATED FUNCTION: https://raw.githubusercontent.com/filamentgroup/loadCSS/master/src/loadCSS.js
/*
https://www.lockedowndesign.com/load-google-fonts-asynchronously-for-page-speed/
<script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.16/webfont.js"></script>
<script>
 WebFont.load({
    google: {
      families: ['Source Sans Pro:400,600,700,400italic,700italic', 'Roboto Condensed:400,700']
    }
  });
</script>
*/
function loadCSS(href, before, media) {
    "use strict";
  var ss = window.document.createElement( "link" );
  var ref = before || window.document.getElementsByTagName( "script" )[ 0 ];
  var sheets = window.document.styleSheets;
  ss.rel = "stylesheet";
  ss.href = href;
  ss.media = "only x";
  ref.parentNode.insertBefore( ss, ref );
  function toggleMedia(){
    var defined;
    for( var i = 0; i < sheets.length; i++ ){
      if( sheets[ i ].href && sheets[ i ].href.indexOf( href ) > -1 ){
        defined = true;
      }
    }
    if( defined ){
      ss.media = media || "all";
    }
    else {
      setTimeout( toggleMedia );
    }
  }
  toggleMedia();
  return ss;
}