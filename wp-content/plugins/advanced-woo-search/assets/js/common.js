(function($){
    "use strict";

    var selector = '.aws-container';
    var instance = 0;
    var pluginPfx = 'aws_opts';
    var translate = {
        sale      : aws_vars.sale,
        noresults : aws_vars.noresults
    };

    $.fn.aws_search = function( options ) {

        var methods = {

            init: function() {

                $('body').append('<div id="aws-search-result-' + instance + '" class="aws-search-result" style="display: none;"></div>');

                methods.mobileClasses();

                setTimeout(function() { methods.resultLayout(); }, 500);

            },

            onKeyup: function(e) {

                searchFor = $searchField.val();
                searchFor = searchFor.trim();
                searchFor = searchFor.replace( /<>\{\}\[\]\\\/]/gi, '' );
                searchFor = searchFor.replace( /\s\s+/g, ' ' );

                for ( var i = 0; i < requests.length; i++ ) {
                    requests[i].abort();
                }

                if ( searchFor === '' ) {
                    $(d.resultBlock).html('').hide();
                    methods.hideLoader();
                    methods.resultsHide();
                    return;
                }

                if ( typeof cachedResponse[searchFor] != 'undefined') {
                    methods.showResults( cachedResponse[searchFor] );
                    return;
                }

                if ( searchFor.length < d.minChars ) {
                    $(d.resultBlock).html('');
                    methods.hideLoader();
                    return;
                }

                if ( d.showLoader ) {
                    methods.showLoader();
                }

                var data = {
                    action: 'aws_action',
                    keyword : searchFor,
                    page: 0
                };

                requests.push(

                    $.ajax({
                        type: 'POST',
                        url: ajaxUrl,
                        data: data,
                        success: function( response ) {

                            var response = $.parseJSON( response );

                            cachedResponse[searchFor] = response;

                            methods.showResults( response );

                            methods.showResultsBlock();

                            methods.analytics( searchFor );

                        },
                        error: function (data, dummy) {
                        }
                    })

                );

            },

            showResults: function( response ) {

                var html = '<ul>';


                if ( ( typeof response.cats !== 'undefined' ) && response.cats.length > 0 ) {

                    $.each(response.cats, function (i, result) {

                        html += '<li class="aws_result_item aws_result_cat">';
                        html += '<a class="aws_result_link" href="' + result.link + '" >';
                        html += '<span class="aws_result_content">';
                        html += '<span class="aws_result_title">';
                        html += result.name;
                        html += '<span class="aws_result_count"> (' + result.count + ')</span>';
                        html += '</span>';
                        html += '</span>';
                        html += '</a>';
                        html += '</li>';

                    });

                }

                if ( ( typeof response.tags !== 'undefined' ) && response.tags.length > 0 ) {

                    $.each(response.tags, function (i, result) {

                        html += '<li class="aws_result_item aws_result_tag">';
                        html += '<a class="aws_result_link" href="' + result.link + '" >';
                        html += '<span class="aws_result_content">';
                        html += '<span class="aws_result_title">';
                        html += result.name;
                        html += '<span class="aws_result_count"> (' + result.count + ')</span>';
                        html += '</span>';
                        html += '</span>';
                        html += '</a>';
                        html += '</li>';

                    });

                }

                if ( ( typeof response.products !== 'undefined' ) && response.products.length > 0 ) {

                    $.each(response.products, function (i, result) {

                        html += '<li class="aws_result_item">';
                        html += '<a class="aws_result_link" href="' + result.link + '" >';

                        if ( result.image ) {
                            html += '<span class="aws_result_image">';
                            html += '<img src="' + result.image + '">';
                            html += '</span>';
                        }

                        html += '<span class="aws_result_content">';
                        html += '<span class="aws_result_title">' + result.title + '</span>';
                        
                        if ( result.stock_status ) {
                            var statusClass = result.stock_status.status ? 'in' : 'out';
                            html += '<span class="aws_result_stock ' + statusClass + '">';
                                html += result.stock_status.text;
                            html += '</span>';
                        }

                        if ( result.sku ) {
                            html += '<span class="aws_result_sku">SKU: ' + result.sku + '</span>';
                        }

                        if ( result.excerpt ) {
                            html += '<span class="aws_result_excerpt">' + result.excerpt + '</span>';
                        }

                        if ( result.price ) {
                            html += '<span class="aws_result_price">' + result.price + '</span>';
                        }

                        html += '</span>';

                        if ( result.on_sale ) {
                            html += '<span class="aws_result_sale">';
                            html += '<span class="aws_onsale">' + translate.sale + '</span>';
                            html += '</span>';
                        }

                        html += '</a>';
                        html += '</li>';

                    });

                    //html += '<li class="aws_result_item aws_search_more"><a href="' + opts.siteUrl + '/?s=' + searchFor + '&post_type=product">View all</a></li>';
                    //html += '<li class="aws_result_item"><a href="#">Next Page</a></li>';

                }

                if ( ( typeof response.cats !== 'undefined' ) && response.cats.length <= 0 && ( typeof response.tags !== 'undefined' ) && response.tags.length <= 0 && ( typeof response.products !== 'undefined' ) && response.products.length <= 0 ) {
                    html += '<li class="aws_result_item aws_no_result">' + translate.noresults + '</li>';
                }


                html += '</ul>';

                methods.hideLoader();

                $(d.resultBlock).html( html );

                methods.showResultsBlock();

            },

            showResultsBlock: function() {
                methods.resultLayout();
                methods.resultsShow();
            },

            showLoader: function() {
                $searchForm.addClass('aws-processing');
            },

            hideLoader: function() {
                $searchForm.removeClass('aws-processing');
            },

            resultsShow: function() {
                $(d.resultBlock).show();
                $searchForm.addClass('aws-form-active');
            },

            resultsHide: function() {
                $(d.resultBlock).hide();
                $searchForm.removeClass('aws-form-active');
            },

            onFocus: function( event ) {
                if ( searchFor !== '' ) {
                    methods.showResultsBlock();
                }
            },

            hideResults: function( event ) {
                if ( ! $(event.target).closest( ".aws-container" ).length ) {
                    methods.resultsHide();
                }
            },

            isResultsVisible:function() {
                return $(d.resultBlock).is(":visible");
            },

            removeHovered: function() {
                $( d.resultBlock ).find('.aws_result_item').removeClass('hovered');
            },

            resultLayout: function () {
                var offset = self.offset();
                var bodyOffset = $('body').offset();
                var bodyPosition = $('body').css('position');

                if ( offset && bodyOffset  ) {

                    var width = self.outerWidth();
                    var top = 0;
                    var left = 0;

                    if ( bodyPosition === 'relative' ) {
                        top = offset.top + $(self).innerHeight() - bodyOffset.top;
                        left = offset.left - bodyOffset.left;
                    } else {
                        top = offset.top + $(self).innerHeight();
                        left = offset.left;
                    }

                    $( d.resultBlock ).css({
                        width : width,
                        top : top,
                        left: left
                    });

                }

            },

            analytics: function( label ) {
                if ( d.useAnalytics ) {
                    try {
                        ga('send', 'event', 'AWS search', 'AWS Search Term', label);
                    }
                    catch (error) {
                    }
                }
            },

            mobileClasses: function() {
                if ( methods.isMobile() ) {
                    $searchForm.addClass('aws-show-clear');
                }
            },

            isMobile: function() {
                var check = false;
                (function(a){if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i.test(a)||/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0,4))) check = true;})(navigator.userAgent||navigator.vendor||window.opera);
                return check;
            }

        };


        var self           = $(this),
            $searchForm    = self.find('.aws-search-form'),
            $searchField   = self.find('.aws-search-field'),
            haveResults    = false,
            requests       = Array(),
            searchFor      = '',
            cachedResponse = new Array();


        var ajaxUrl = ( self.data('url') !== undefined ) ? self.data('url') : false;


        if ( options === 'relayout' ) {
            var d = self.data(pluginPfx);
            methods.resultLayout();
            return;
        }


        instance++;

        self.data( pluginPfx, {
            minChars  : ( self.data('min-chars')   !== undefined ) ? self.data('min-chars') : 1,
            showLoader: ( self.data('show-loader') !== undefined ) ? self.data('show-loader') : true,
            showPage: ( self.data('show-page') !== undefined ) ? self.data('show-page') : true,
            useAnalytics: ( self.data('use-analytics') !== undefined ) ? self.data('use-analytics') : false,
            instance: instance,
            resultBlock: '#aws-search-result-' + instance
        });


        var d = self.data(pluginPfx);



        if ( $searchForm.length > 0 ) {
            methods.init.call(this);
        }


        $searchField.on( 'keyup', function(e) {
            if ( e.keyCode != 40 && e.keyCode != 38 ) {
                methods.onKeyup(e);
            }
        });


        $searchField.on( 'focus', function (e) {
            methods.onFocus(e);
        });


        $searchForm.on( 'keypress', function(e) {
            if ( e.keyCode == 13 && ! d.showPage ) {
                e.preventDefault();
            }
        });


        $searchForm.find('.aws-search-clear').on( 'click', function (e) {
            $searchField.val('');
            $searchField.focus();
            methods.resultsHide();
            $(d.resultBlock).html('');
            searchFor = '';
        });


        $(document).on( 'click', function (e) {
            methods.hideResults(e);
        });


        $(window).on( 'resize', function(e) {
            methods.resultLayout();
        });


        $(window).on( 'scroll', function(e) {
            if ( $( d.resultBlock ).css('display') == 'block' ) {
                methods.resultLayout();
            }
        });


        $( d.resultBlock ).on( 'mouseenter', '.aws_result_item', function() {
            methods.removeHovered();
            $(this).addClass('hovered');
        });


        $( d.resultBlock ).on( 'mouseleave', '.aws_result_item', function() {
            methods.removeHovered();
        });


        $(window).on( 'keydown', function(e) {

            if ( e.keyCode == 40 || e.keyCode == 38 ) {
                if ( methods.isResultsVisible() ) {

                    e.stopPropagation();
                    e.preventDefault();

                    var $item = $( d.resultBlock ).find('.aws_result_item');
                    var $hoveredItem = $( d.resultBlock ).find('.aws_result_item.hovered');
                    var $itemsList = $( d.resultBlock ).find('ul');

                    if ( e.keyCode == 40 ) {

                        if ( $hoveredItem.length > 0 ) {
                            methods.removeHovered();
                            $hoveredItem.next().addClass('hovered');
                        } else {
                            $item.first().addClass('hovered');
                        }

                    }

                    if ( e.keyCode == 38 ) {

                        if ( $hoveredItem.length > 0 ) {
                            methods.removeHovered();
                            $hoveredItem.prev().addClass('hovered');
                        } else {
                            $item.last().addClass('hovered');
                        }

                    }

                    var scrolledTop = $itemsList.scrollTop();
                    var position = $( d.resultBlock ).find('.aws_result_item.hovered').position();

                    if ( position ) {
                        $itemsList.scrollTop( position.top + scrolledTop )
                    }


                }
            }

        });


    };


    // Call plugin method
    $(document).ready(function() {

        $(selector).each( function() {
            $(this).aws_search();
        });

    });


})( jQuery );