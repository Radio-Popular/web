
var addEvent = function( obj, type, fn ) {
  if (obj.addEventListener)
    obj.addEventListener(type, fn, false);
  else if (obj.attachEvent) 
    obj.attachEvent('on' + type, function() { return fn.apply(obj, new Array(window.event));});
}



addEvent(window, 'load', function() {
  if (document.getElementById) {

    document.getElementById('menu-popup-player').onclick = function() {
      window.open(this.href, this.target, 'width=380,height=250');
      return false;
    };

  }
});

