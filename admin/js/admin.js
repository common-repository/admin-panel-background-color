/**
 * @fileOverview Admin Panel Background Color Wordpress plugins admin side javascript functionality
 * @author castellar120
 * @version: 1.0
 */
(function( $ ) {

  'use strict';
  $( window ).load(function () {
    /**
     * Create color picker
     */
    const myPicker = new jQuery.ColorPicker('#colorpicker', {
      color: '#f1f1f1',
      imagepath: phpVars.pluginsUrl+'/admin-panel-background-color/admin/js/HSV-HEX-Color-Picker-jQuery/',
        change: function (hex) {
            $('#abc-background-color').val(hex);
            $('body').css('background-color',hex);
        }
    });

    /**
     * Insert selected value into color picker
     */
    $('#abc-background-color').val(phpVars.activeBackground);
    myPicker.hex(phpVars.activeBackground);

    /**
     * Insert selected value into color picker
     */
    $('body').css('background-color',phpVars.activeBackground);

    var abcolorColor1Options = {
      defaultColor: false,
      change: function(event, ui){
        // We can also pass the url value separately from ajaxurl for front end AJAX implementations
        //Some event will trigger the ajax call, you can push whatever data to the server, simply passing it to the "data" object in ajax call
          $.ajax({
            url: phpVars.ajaxurl, // this is the object instantiated in wp_localize_script function
            data:{
              'action': 'compileScssAjax', // this is the function in your functions.php that will be triggered,
              'c1': '#' + ui.color._color.toString(16)
            },
            success: function( response )
            {
              $('.abcolor_style').text(response);
            }
          });
      },
      clear: function() {},
      hide: true,
      palettes: true
    };

    var abcolorColor2Options = {
      defaultColor: false,
      change: function(event, ui){
          $.ajax({
            url: phpVars.ajaxurl, // this is the object instantiated in wp_localize_script function
            data:{
              'action': 'compileScssAjax', // this is the function in your functions.php that will be triggered,
              'c2': '#' + ui.color._color.toString(16)
            },
            success: function( response )
            {
              $('.abcolor_style').text(response);
            }
          });
      },
      clear: function() {},
      hide: true,
      palettes: true
    };

    var abcolorColor3Options = {
      defaultColor: false,
      change: function(event, ui){
          $.ajax({
            url: phpVars.ajaxurl, // this is the object instantiated in wp_localize_script function
            data:{
              'action': 'compileScssAjax', // this is the function in your functions.php that will be triggered,
              'c3': '#' + ui.color._color.toString(16)
            },
            success: function( response )
            {
              $('.abcolor_style').text(response);
            }
          });
      },
      clear: function() {},
      hide: true,
      palettes: true
    };

    var abcolorColor4Options = {
      defaultColor: false,
      change: function(event, ui){
          $.ajax({
            url: phpVars.ajaxurl, // this is the object instantiated in wp_localize_script function
            data:{
              'action': 'compileScssAjax', // this is the function in your functions.php that will be triggered,
              'c4': '#' + ui.color._color.toString(16)
            },
            success: function( response )
            {
              $('.abcolor_style').text(response);
            }
          });
      },
      clear: function() {},
      hide: true,
      palettes: true
    };

    $("#abcolor_color1").wpColorPicker(abcolorColor1Options);
    $("#abcolor_color2").wpColorPicker(abcolorColor2Options);
    $("#abcolor_color3").wpColorPicker(abcolorColor3Options);
    $("#abcolor_color4").wpColorPicker(abcolorColor4Options);

    $('#abcolor_color1').iris('color', phpVars.color1);
    $('#abcolor_color2').iris('color', phpVars.color2);
    $('#abcolor_color3').iris('color', phpVars.color3);
    $('#abcolor_color4').iris('color', phpVars.color4);
  });
})( jQuery );
