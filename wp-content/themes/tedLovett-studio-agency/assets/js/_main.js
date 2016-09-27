(function($) {

  /* Global Vars */

    var $win                   = $(window),
        $bod                   = $('body'),
        $nav                   = $('header nav'),
        $clientLink            = $nav.find('.menu-clients'),
        $clientNav             = $clientLink.find('.dropdown-menu'),
        state,
        stateOnDocumentLoad    = false,
        isUserInitiatedScroll  = false,
        isNavInitiatedScroll   = false,
        $lightbox;

  /* Functions */

    function nav_updater(element) {

      var thiis = element,
          rent  = thiis.parents('.dropdown-menu li.dropdown');

      $clientNav.find('.current').removeClass('current');

      if (thiis.hasClass('link-portfolio-piece')) {

        /* Is a portfolio link */

          thiis.addClass('current');

      }

      if (!rent.hasClass('showSub')) {

        $clientNav.find('.showSub').removeClass('showSub');

        rent.addClass('showSub');
      }

      if ($clientLink.hasClass('showSub')) {

        $clientLink.removeClass('showSub');
      }
    }

    function state_handler() {

      /* Do things when the state has changed */

        state = History.getState();

        var url       = state.url,
            id        = url.split('?project=')[1],
            section   = $('#' + id),
            anchorUrl = window.location.protocol + '//' + window.location.host + window.location.pathname + '#' + id,
            anchor    = $nav.find('a[href="' + anchorUrl + '"]');

        /* Update Nav */

          if (!isNavInitiatedScroll) {

            nav_updater(anchor);
          }

        /* Scroll */

          if (!isUserInitiatedScroll) {

            isNavInitiatedScroll = true;

            $bod.addClass('__meltHeader');

            $bod.velocity('scroll', {
              duration: 700,
              offset: section.offset().top,
              complete: function() {

                isNavInitiatedScroll = false;

                $bod.removeClass('__meltHeader');

                section.find('img[data-tla-lazy-src]').each(function() {

                  tla_lazyLoad_image(this);
                });
              }
            });
          }

          isUserInitiatedScroll = false;
    }

    function state_createValuesFromElementThenPush(element, title) {

      /* Create and push a state */

        var thiis = element,
            id    = thiis.attr('id'),
            hash;

        if (thiis.attr('href')) {

          id = thiis.attr('href').split('#')[1];
        }

        hash = '?project=' + id;

        if (id) {

          if (state) {

            History.replaceState('', title, hash);

          } else {

            History.pushState('', title, hash);
          }
        }
    }

    function tla_lazyLoad_image(img) {

      var $img = $(img),
          src  = $img.attr('data-tla-lazy-src');

      $img
        .addClass('tla-lazy-loading')
        .removeAttr('data-tla-lazy-src')
        .attr('data-tla-lazy-loaded', 'true');

      img.src = src;

      imagesLoaded(img, function() {

        $img.removeClass('tla-lazy-loading');
      });
    }

    function lightbox_remove() {

      $bod.removeClass('__lightbox-active');

      $('.lightboxed').removeClass('lightboxed');

      setTimeout(function() {

        $lightbox.find('img').remove();
      }, 301);
    }

    function lightbox_buildNew(source, positionTop, positionLeft, displayWidth, naturalWidth) {

      if (!$bod.hasClass('__lightbox-active')) {

        if (!$lightbox) {

          $lightbox = $('<figure id="lightbox"></figure>');

          $bod.append($lightbox);
        }

        $bod.addClass('__lightbox-active');

        var $newImg = $('<img />'),
            $oldImg = $lightbox.find('img');

        if ($oldImg) {

          $oldImg.remove();
        }

        $newImg
          .addClass('__loading')
          .attr({
            'src': source,
            'width': displayWidth,
          })
          .css({
            'width': displayWidth,
            'top': positionTop,
            'left': positionLeft,
          })
          .appendTo($lightbox);

        imagesLoaded($lightbox, function() {

          $newImg
            .removeClass('__loading')
            .css({
              'width': naturalWidth,
              'top': 0,
              'left': 0
            });
        });

        $lightbox.click(function(){

          lightbox_remove();
        });
      }
    }

  /* Page Init */

    var tla  = {
        common: {
          init: function() {

            $('a.dropdown-toggle').click(function(e){

              e.preventDefault();

              $(this).parent('li').toggleClass('showSub');
            });
          }
        },
        clients: {
          init: function() {

            /* State changes */

              History.Adapter.bind(window, 'statechange', function() {

                state_handler();
              });

            /* Doc Load State */

              state = History.getState();

              if (state.hash != window.location.pathname) {

                stateOnDocumentLoad = true;
              }

            /* Nav clicks */

              $clientNav.on('click', 'a', function(e){

                e.preventDefault();

                var thiis = $(this),
                    title;

                // if (!thiis.hasClass('link-portfolio-piece')) {

                //   thiis.next('ul').find('li:first-of-type a').click();
                // } else {

                  title = thiis.html();

                  state_createValuesFromElementThenPush(thiis, title);
                // }
              });

            /* Sections in view */

              $('.clientProjectContainer').bind('scrollin', function() {

                var thiis = $(this),
                    title = thiis.find('.clientProject.name').data('project-nice-name');

                if (!stateOnDocumentLoad) {

                  if (!isNavInitiatedScroll) {

                    isUserInitiatedScroll = true;

                    thiis.addClass('visible');

                    state_createValuesFromElementThenPush(thiis, title);

                    thiis.find('img[data-tla-lazy-src]').each(function() {

                      tla_lazyLoad_image(this);
                    });
                  }
                }

              }).bind('scrollout', function() {

                $(this).removeClass('visible');
              });

              // $('.clientContainer').bind('scrollin', function() {

              //   $(this).addClass('current');

              // }).bind('scrollout', function() {

              //   $(this).removeClass('current');
              // });

            /* Lightbox */

              $('.clientProjectBody a').click(function(e) {

                e.preventDefault();

                var thiis  = $(this);

                if (!thiis.hasClass('lightboxed')) {

                  thiis.addClass('lightboxed');

                  var highRes       = thiis.attr('href'),
                      boundingRect  = this.getBoundingClientRect(),
                      displayWidth  = boundingRect.width,
                      top           = boundingRect.top,
                      left          = boundingRect.left,
                      naturalWidth  = thiis.find('img').attr('width');

                  lightbox_buildNew(highRes, top, left, displayWidth, naturalWidth);
                } else {

                  thiis.removeClass('lightboxed');

                  lightbox_remove();
                }
              });

            /* Window load */

              $win.load(function() {

                /* Trigger state change */

                  if (stateOnDocumentLoad) {

                    var anchorUrl = window.location.protocol + '//' + window.location.host;

                    if (state.hash.indexOf('#') > -1) {

                      anchorUrl = anchorUrl + state.hash;

                    } else {

                      anchorUrl = anchorUrl + state.hash.replace('?project=', '#');
                    }

                    $nav.find('a[href="' + anchorUrl + '"]').click();

                    stateOnDocumentLoad = false;
                  }
              });
          }
        }
      },
      UTIL = {
        fire: function(func, funcname, args) {
          var namespace = tla;
          funcname = (funcname === undefined) ? 'init' : funcname;
          if (func !== '' && namespace[func] && typeof namespace[func][funcname] === 'function') {
            namespace[func][funcname](args);
          }
        },
        loadEvents: function() {
          UTIL.fire('common');

          $.each(document.body.className.replace(/-/g, '_').split(/\s+/),function(i,classnm) {
            UTIL.fire(classnm);
          });
        }
      };

  $(document).ready(UTIL.loadEvents);

})(jQuery);
