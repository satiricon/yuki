(function ($) {

  function playlist_build(i, element){
    const $element = $(element);
    const song = {
      sources: [{
        src: $element.data('url'),
        type: 'application/dash+xml',
      }]
    };
    this.push(song);
  }

  function playlist_click(event) {
    const $element = $(event.currentTarget);
    this.playlist.currentItem($element.data('index'));
    $('.playlist__song', $element.closest('.playlist'))
      .removeClass('playlist__song--playing');
    $element.parent().addClass('playlist__song--playing');

  }

  $(function () {
    const player = videojs('player',
      {
        controls: true,
        autoplay: false,
        loop: false,
        fluid: false,
        width: 600,
        height: 300,
      });
    let playlist = [];
    $('.playlist__song a')
      .each(playlist_build.bind(playlist))
      .click(playlist_click.bind(player));
    player.playlist(playlist);
    player.playlist.autoadvance(0);
  });

})(jQuery);

/*player.playlist([
  {sources: [{
    src: 'https://karen.david.desk/yuki/media/files/home/david/tmp/Surf/Instrumental%20Surf%20Rock/Gary%20Hoey/Monster%20Surf/Gary%20Hoey%20-%20Monster%20Surf%20-%2005%20-%20Peter%20Gunn.mp3',
    type: 'audio/mp3'
  }]},
  {sources: [{
    src: 'http://media.w3.org/2010/05/sintel/trailer.mp4',
    type: 'video/mp4'
  }]},
]);*/


