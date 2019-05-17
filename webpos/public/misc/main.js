Barba.Pjax.init();

var menu = document.querySelector('.menu');
var items = menu.querySelectorAll('li');
//$(items).click(function() { NProgress.start(); });
var wrapper = document.getElementById('barba-wrapper');

Barba.Dispatcher.on('transitionCompleted', function() {
	initAll()
// NProgress.done();
})

 