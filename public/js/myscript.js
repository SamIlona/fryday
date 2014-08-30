$(window).load(function() {
  // Grayscale 
  $('.flickrs img, .greyScale, .hover_img img').greyScale({
    // call the plugin with non-defult fadeTime (default: 400ms)
    fadeTime: 100,
    reverse: false
  }); 
});
$(document).ready(function(){ 
  //build dropdown
  $("<select />").appendTo("nav#main_menu div");
  
  // Create default option "Go to..."
  $("<option />", {
     "selected": "selected",
     "value"   : "",
     "text"    : "Please choose page"
  }).appendTo("nav#main_menu select");  
  
  // Populate dropdowns with the first menu items
  $("nav#main_menu li a").each(function() {
    var el = $(this);
    $("<option />", {
        "value"   : el.attr("href"),
        "text"    : el.text()
    }).appendTo("nav#main_menu select");
  });
  
  //make responsive dropdown menu actually work     
    $("nav#main_menu select").change(function() {
      window.location = $(this).find("option:selected").val();
    });
  
  //Iframe transparent
  $("iframe").each(function(){
    var ifr_source = $(this).attr('src');
    var wmode = "wmode=transparent";
    if(ifr_source.indexOf('?') != -1) {
    var getQString = ifr_source.split('?');
    var oldString = getQString[1];
    var newString = getQString[0];
    $(this).attr('src',newString+'?'+wmode+'&'+oldString);
    }
    else $(this).attr('src',ifr_source+'?'+wmode);
  });
      
  //Twitter Setup
  $(".tweet_block").tweet({
    join_text: "auto",
    username: "envato",
    avatar_size: 0,
    count: 3,
    auto_join_text_default: "",
    auto_join_text_ed: "",
    auto_join_text_ing: "",
    auto_join_text_reply: "",
    auto_join_text_url: "",
    loading_text: "loading tweets..."
  }); 
  
  //Flickr Integration
    $.getJSON("http://api.flickr.com/services/feeds/photos_public.gne?id=89129181@N06&lang=en-us&format=json&jsoncallback=?", function(data){
    $.each(data.items, function(i,item){
      if(i<=11){ // <— change this number to display more or less images
        $("<img/>").attr("src", item.media.m.replace('_m', '_s')).appendTo(".FlickrImages ul")
        .wrap("<li><a href='" + item.link + "' target='_blank' title='Flickr'></a></li>");
      }
    });     
    }); 
  
  //prettyPhoto
  $("a[rel^='prettyPhoto']").prettyPhoto();
  
  
  //Tooltip
  $('.follow_us a').tooltip();
              
}); 