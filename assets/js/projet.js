import $ from 'jquery';

$('.projet-img div').click((event) => {
  $('.projet-img div').removeClass('active');
  $(event.currentTarget).addClass('active');
  const src = $(event.currentTarget).attr('data-src');

  $('.un-projet img').fadeOut();

  setTimeout(() => {
    $('.un-projet img').attr('src', src).fadeIn();
  }, 500);
});