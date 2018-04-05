jQuery(document).ready(function ($) {
    'use strict';

    var $reindexBlock = $('#aws-reindex');
    var $reindexBtn = $('#aws-reindex .button');
    var $reindexProgress = $('#aws-reindex .reindex-progress');
    var $reindexCount = $('#aws-reindex-count strong');
    var syncStatus;
    var processed;
    var toProcess;
    var syncData = false;

    var $clearCacheBtn = $('#aws-clear-cache .button');


    // Reindex table
    $reindexBtn.on( 'click', function(e) {

        e.preventDefault();

        syncStatus = 'sync';
        toProcess  = 0;
        processed = 0;

        $reindexBlock.addClass('loading');
        $reindexProgress.html ( processed + '%' );

        sync('start');

    });


    function sync( data ) {

        $.ajax({
            type: 'POST',
            url: ajaxurl,
            data: {
                action: 'aws-reindex',
                data: data
            },
            dataType: "json",
            success: function (response) {
                if ( 'sync' !== syncStatus ) {
                    return;
                }

                toProcess = response.data.found_posts;
                processed = response.data.offset;

                processed = Math.floor( processed / toProcess * 100 );

                syncData = response.data;

                if ( 0 === response.data.offset && ! response.data.start ) {

                    // Sync finished
                    syncStatus = 'finished';

                    console.log( response.data );
                    console.log( "Reindex finished!" );

                    $reindexBlock.removeClass('loading');

                    $reindexCount.text( response.data.found_posts );

                } else {

                    console.log( response.data );

                    $reindexProgress.html( processed + '%' );

                    // We are starting a sync
                    syncStatus = 'sync';

                    sync( response.data );
                }

            },
            error : function( jqXHR, textStatus ) {
                console.log( "Request failed: " + textStatus );

                if ( textStatus == 'timeout' || jqXHR.status == 504 ) {
                    console.log( 'timeout' );
                    if ( syncData ) {
                        setTimeout(function() { sync( syncData ); }, 1000);
                    }
                }

            },
            complete: function () {
            }
        });

    }

    // Clear cache
    $clearCacheBtn.on( 'click', function(e) {

        e.preventDefault();

        var $clearCacheBlock = $(this).closest('#aws-clear-cache');

        $clearCacheBlock.addClass('loading');

        $.ajax({
            type: 'POST',
            url: ajaxurl,
            data: {
                action: 'aws-clear-cache'
            },
            dataType: "json",
            success: function (data) {
                alert('Cache cleared!');
                $clearCacheBlock.removeClass('loading');
            }
        });

    });


});