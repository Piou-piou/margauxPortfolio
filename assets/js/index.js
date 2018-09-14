import {TweenMax, Power2, TimelineLite} from 'gsap/TweenMax';
import $ from 'jquery';

$(".index").mousemove(function(e) {
  parallaxIt(e, ".ronds", -100);
  parallaxIt(e, ".dessins", -30);
});

function parallaxIt(e, target, movement) {
  var $this = $(".index");
  var relX = e.pageX - $this.offset().left;
  var relY = e.pageY - $this.offset().top;

  TweenMax.to(target, 1, {
    x: (relX - $this.width() / 2) / $this.width() * movement,
    y: (relY - $this.height() / 2) / $this.height() * movement
  });
}