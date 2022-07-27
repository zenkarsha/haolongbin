var $obj = $('#rotate_object'),
    $obj_truesize = $('#role_truesize'),
    $preview = $('#previewimg'),
    $preview_bg = $('.preview_bg'),
    $container = $('#rotate_container'),
    $truesize = $("#truesize"),
    $loading = $('#loading'),
    $rotate_copy = $('#rotateCopy'),
    $generating = $('#generating');

  var init_width, init_height, init_size, init_x, init_y;

$(window).load(function()
{
  $loading.show();

  $obj_truesize.attr('src','images/object/1.png');

  init_width = $obj_truesize.width();
  init_height = $obj_truesize.height();
  init_size = .58;
  init_x = .9;
  init_y = .63;

  var image_w = $preview.width(),
      image_h = $preview.height();

  $container.css('height',image_h).css('width',image_w);
  $preview_bg.css('height',image_h).css('width',image_w);

  var true_w = $truesize.attr('src', $preview.attr('src')).width(),
      scale = image_w/true_w,
      h = image_h*init_size,
      w = h*(init_width/init_height),
      left = image_w*init_x - w,
      top = image_h*init_y - h;

  $obj.css('width',w).css('height',h);
  $obj.css('left',left).css('top',top);

  RefreshObjectInfo();
  $loading.hide();
  $container.fadeTo('fast',1);
});

$(document).ready(function()
{
  $('#role_select').ddslick({
    onSelected: function(data){
      $loading.show();
      $('#role').val(data.selectedData.value);
      var role_url = 'images/object/' + data.selectedData.value + '.png';
      $obj_truesize.load(function()
      {
        var init_width = $obj_truesize.width(),
            init_height = $obj_truesize.height(),
            image_h = $preview.height();

        var h = image_h*init_size,
            w = h*(init_width/init_height);
            $obj.css('width',w).css('height',h);

        $('#object_img').attr('src',role_url).load(function() {
          RefreshObjectInfo();
          $loading.hide();
        });

      }).attr('src',role_url);
    }
  });
  $obj.resizable({
    handles: 'ne, nw, se, sw',
    containment: 'parent',
    // aspectRatio: true,
    // autoHide: true
  });
  $obj.draggable({
    // containment: 'parent',
    // axis: 'x'
  });
  $obj.rotatable();

  $("#normalSubmit").click(function() {
    $('#directpost').val('');
    $('#form').attr("target","_self").submit();
  });
  // $("#facebookSubmit").click(function() {
  //   $('#directpost').val('1');
  //   $('#form').attr("target","_blank").submit();
  // });
  $("#genbgSubmit").click(function() {
    $generating.show();
    $loading.show();
    $.ajax({
      url: 'genbackground',
      dataType: 'html',
      type:'POST',
      data: {
        source: $("#source").val(),
        part_x: $("#part_x").val(),
        part_y: $("#part_y").val(),
        part_w: $("#part_w").val(),
        part_h: $("#part_h").val(),
        degrees: $("#degrees").val(),
        role: $("#role").val()
      },
      success: function(response){
        var filename = response;
        $('#filename').attr('value',filename);
        $('#source').attr('value',filename);
        $('#previewimg').attr('src','upload/'+filename).load(function()
        {
          // $obj.css('left', 10).css('top',10);
          RefreshObjectInfo();
          $generating.fadeOut();
          $loading.hide();
        });
      }
    });
  });
  $('body').mouseup(function(){
    RefreshObjectInfo();
  });
});

//window resize event
var window_w = $(window).width();
$(window).resize(function()
{
  var new_window_w = $(window).width();
  if(window_w !== new_window_w)
  {
    var image_w = $preview.width(),
        image_h = $preview.height();
    $container.css('height',image_h).css('width',image_w);
    $preview_bg.css('height',image_h).css('width',image_w);

    var true_w = $truesize.attr('src', $preview.attr('src')).width(),
        scale = image_w/true_w,
        h = image_h*init_size,
        w = h*(init_width/init_height),
        left = image_w*init_x - w,
        top = image_h*init_y - h;

    $obj.css('width',w).css('height',h);
    $obj.css('left',left).css('top',top);

    RefreshObjectInfo();
    window_w = new_window_w;
  }
});

//uploader
$(function(){
  $('.uploadBtn').click(function(){
    $('#uploadInput').click();
  });
  $('#uploadInput, .drop').fileUpload({
    url: 'uploader',
    type: 'POST',
    dataType: 'json',
    beforeSend: function () {
      $('#uploading').fadeIn();
      $loading.show();
    },
    complete: function () {
    },
    success: function (result, status, xhr) {
      var filename = result.filename;
      $('#filename').attr('value',filename);
      $('#source').attr('value',filename);
      $preview.attr('src','upload/'+filename).load(function()
      {
        $container.css('height','inherit').css('width','inherit');
        $preview_bg.css('height','inherit').css('width','inherit');

        var image_w = $preview.width(),
            image_h = $preview.height();
        $container.css('height',image_h).css('width',image_w);
        $preview_bg.css('height',image_h).css('width',image_w);

        if(init_height > image_h) {
          var scale = $obj.width()/$obj.height(),
              new_h = image_h*init_size,
              new_w = new_h*scale;
        }
        else if(init_width > image_w) {
          var scale = $obj.height()/$obj.width(),
              new_w = image_w*init_size,
              new_h = new_w*scale;
        }
        else {
          var new_h = init_height,
              new_w = init_width;
        }

        var left = image_w*init_x - new_w,
            top = image_h*init_y - new_h;

        $obj.css('width',new_w).css('height',new_h);
        $obj.css('left',left).css('top',top);

        RefreshObjectInfo();
        $('#uploading').fadeOut();
        $loading.hide();
      });
    }
  });
});
if(isMobile())
{
  $container.click(function(){
    if($('.ui-resizable-handle').css('display')=='none') {
      $('.ui-resizable-handle').show();
      $('.ui-rotatable-handle').show();
    }
    else{
      $('.ui-resizable-handle').hide();
      $('.ui-rotatable-handle').hide();
    }
  });
}
else {
  $container.mouseenter(function(){
      $('.ui-resizable-handle').show();
      $('.ui-rotatable-handle').show();
  });
  $container.mouseleave(function(){
      $('.ui-resizable-handle').hide();
      $('.ui-rotatable-handle').hide();
  });
}


function isMobile()
{
  return (
    (navigator.userAgent.match(/Android/i)) ||
    (navigator.userAgent.match(/webOS/i)) ||
    (navigator.userAgent.match(/iPhone/i)) ||
    (navigator.userAgent.match(/iPod/i)) ||
    (navigator.userAgent.match(/iPad/i)) ||
    (navigator.userAgent.match(/BlackBerry/))
  );
}
function getImgSize(src)
{
  var newImg = new Image();
  newImg.src = src;
  var img_w = newImg.width;
  var img_h = newImg.height;
  return [img_w, img_h];
}
function RefreshObjectInfo()
{
  var image_w = $preview.width();
  var true_w, scale=1;
  true_w=$truesize.attr("src", $preview.attr("src")).width();
    scale = image_w/true_w;

  var w = $obj.width()/scale+1;
  var h = $obj.height()/scale+1;

  $("#part_w").attr('value',w);
  $("#part_h").attr('value',h);

  getRotateTruePosition();
}
function getRotateTruePosition()
{
  var degree = getRotationDegrees($obj);
  $("#degrees").val(degree);

  var image_w = $preview.width();
  var image_h = $preview.height();
  $rotate_copy.css('height',image_h).css('width',image_w);
  var rotate_container = $container.html();
  $rotate_copy.html(rotate_container);

  var true_w, scale=1;
  true_w=$truesize.attr("src", $preview.attr("src")).width();
    scale = image_w/true_w;

  var copy_position = $rotate_copy.offset();
  var copy_obj_position = $rotate_copy.find('#rotate_object').offset();
  var true_x = (copy_obj_position.left-copy_position.left)/scale;
  var true_y = (copy_obj_position.top-copy_position.top)/scale;

  $("#part_x").attr('value',true_x);
  $("#part_y").attr('value',true_y);
}
function getRotationDegrees(obj)
{
  var matrix = obj.css("-webkit-transform") ||
  obj.css("-moz-transform") ||
  obj.css("-ms-transform") ||
  obj.css("-o-transform") ||
  obj.css("transform");
  if(matrix !== 'none') {
      var values = matrix.split('(')[1].split(')')[0].split(',');
      var a = values[0];
      var b = values[1];
      var angle = Math.round(Math.atan2(b, a) * (180/Math.PI));
  } else { var angle = 0; }
  return (angle < 0) ? angle +=360 : angle;
}

//mobile preview
$("#show").click(function() {
  if($(".preview").css('z-index') == '1200') {
    $(".preview").css('z-index','-9999').css('opacity',0);
  }
  else if($(".preview").css('z-index') == '-9999') {
    $(".preview").css('z-index','1200').css('opacity',1);
  }
});
