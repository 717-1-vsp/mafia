
$(window).on('load', function(){
    var audio = document.getElementById('audio');
    if (audio) audio.play();
    setTimeout(removeLoader, 2000); //wait for page load PLUS two seconds.
  });
function removeLoader(){
      $( "#loading" ).fadeOut(500, function() {
        // fadeOut complete. Remove the loading div
        $( "#loading" ).remove(); //makes page more lightweight 
    });  
}


  

