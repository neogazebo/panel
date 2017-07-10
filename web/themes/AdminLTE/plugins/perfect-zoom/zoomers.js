$(document).ready(function(){
      var iv1 = $("#viewer").iviewer({
           src: "test_image.jpg",
           update_on_resize: false,
           zoom_animation: false,
           mousewheel: false,
           onMouseMove: function(ev, coords) { },
           onStartDrag: function(ev, coords) { return false; }, //this image will not be dragged
           onDrag: function(ev, coords) { }
      });

       $("#in").click(function(){ iv1.iviewer('zoom_by', 1); });
       $("#out").click(function(){ iv1.iviewer('zoom_by', -1); });
       $("#fit").click(function(){ iv1.iviewer('fit'); });
       $("#orig").click(function(){ iv1.iviewer('set_zoom', 100); });
       $("#update").click(function(){ iv1.iviewer('update_container_info'); });

      var iv2 = $("#viewer2").iviewer(
      {
          src: "test_image2.jpg"
      });

      $("#chimg").click(function()
      {
        iv2.iviewer('loadImage', "test_image.jpg");
        return false;
      });

      var fill = false;
      $("#fill").click(function()
      {
        fill = !fill;
        iv2.iviewer('fill_container', fill);
        return false;
      });
});
