$(document).ready(function(){
    $('#supersized-loader').hide();
    $('#supersized').hide();
});
    var fbAuthResp;
    window.fbAsyncInit = function() {
        FB.init({
            appId: appId,
            // App ID
            status: true,
            // check login status
            cookie: true,
            // enable cookies to allow the server to access the session
            xfbml: true // parse XFBML
        });

    // Additional initialization code here
    };

    // Load the SDK Asynchronously
    (function(d) {
        var js, id = 'facebook-jssdk',
        ref = d.getElementsByTagName('script')[0];
        if (d.getElementById(id)) {
            return;
        }
        js = d.createElement('script');
        js.id = id;
        js.async = true;
        js.src = "//connect.facebook.net/en_US/all.js";
        ref.parentNode.insertBefore(js, ref);
    }(document));

    //Authanticate User with app 

    $("#fblogin").click(function() {
        $('#supersized-loader').show();
        FB.login(function(response) {
            if (response.authResponse) {
                fbAuthResp=response;
                //Set Accesstoken of user in session
                $.ajax({
                    url: 'fbprocess.php',
                    type: 'post',
                    data: {
                        'accesstoken' : response.authResponse.accessToken
                    },
                    success: function(data) {
            			
                    }
                });
                //Get User Name
                FB.api('/me?fields=name',function(respo){
                    $("#title").html(respo.name + "'s Albums");
                    $("#fblogin").hide();
                    //Get All ablums of user
                    FB.api('/me/albums', showAlbums);
                } );
            
            
            } else {
                //User close auth window
                $('#supersized-loader').hide();
                alert('User cancelled login or did not fully authorize.');
            }
        }, {
            scope: 'email,user_photos,friends_photos'
        });

    });

    /**
 * This function process response of /me/albums and display it in to grid
 */
    function showAlbums(response) {
        $('#galleryLoading').hide();
        $('#container').show();
        $('#supersized-loader').hide();
        $.each(response.data, function(key, value) {
            //create html structure
            var strHtml = '' + '<div id="album_' + key + '" class="span3"> ' + '<a href="#" class="album_link_' + key + '"><img class="imgcover" id="album_cover_' + key + '" /></a>' + '<img id="loading_' + key + '" src="/img/ajax-loader.gif" />' + '<a href="#" class="album_link_' + key + '"><h2>' + value.name + '</h2></a>' + '<p>' + value.count + ' photos</p><button id="download_album_' + key + '" class="btn btn-success icon arrowdown">Download</button>' + '</div>';

            $('#albums').append(strHtml);
            FB.api('/' + value.cover_photo + '', function(response) {
                if (!response.picture) {
                    $('#album_' + key).hide();
                } else {
                    $('#loading_' + key).hide();
                    $('#album_cover_' + key).attr("src", response.picture);
                }
            });
            $('.album_link_' + key).click(function(event) {
                event.preventDefault();
                show_albums_photos(value.id);
            });
            $('#download_album_' + key).click(function(event) {
                event.preventDefault();
                downloadAlbum(value.id);
            });


        });
    }

    /**
 * To start downalod all images and zip in to file
 */
    function downloadAlbum(albumId){
        $("#downloadlink").hide();
        $("#downloadprogress").show();
        $("#openmodel").click();
        $.ajax({
            url: 'fbprocess.php?albumid=' + albumId,
            type: 'get',
            timeout: 18000000,
            success: function(data) {
                //show download button
                $("#downloadprogress").hide();
                $("#downloadlink").show();
                $("#hrefDownload").attr('href',albumId + '.zip');
            },
            error:function(data){
                //Handle error
                $('#modelclose').click();
                alert('Error Occure on server,Please Try again')
            }
        });
    }
    //get all photos for an album and hide the album view

    var lastAlbumId;
    function show_albums_photos(album_id) {

        lastAlbumId=album_id;
        $('#loading_gallery').show();
        $('#connect').hide();
        $('#navbar').hide();
    
        $('#supersized-loader').show();
        $('#supersized').show();
        if ($('#album_' + album_id).length > 0) {
            $('#album_' + album_id).show();
        } else {
            FB.api('/' + album_id + '/photos', function(response) {
                var arrPhotos=[];
                // console.log(response.data);
                $.each(response.data, function(key, value) {
                    arrPhotos.push({
                        image:value.source,
                        title:(value.name!=undefined)?value.name:'',
                        thumb:value.picture,
                        url:value.link
                    })
                });
                $('#loading_gallery').hide();
                jQuery(function($){
                    $.supersized({
                        slide_interval          :   8000,		// Length between transitions
                        transition              :   1, 			// 0-None, 1-Fade, 2-Slide Top, 3-Slide Right, 4-Slide Bottom, 5-Slide Left, 6-Carousel Right, 7-Carousel Left
                        transition_speed		:	700,		// Speed of transition
                        // Components							
                        slide_links				:	'blank',	// Individual links for each slide (Options: false, 'num', 'name', 'blank')
                        slides 					: arrPhotos
					
                    });
                });
            });
            $('#slider').show();

        }
    }
    //back to album from full screen slideshow
    $("#backtoalbum").click(function(){
        $('#supersized-loader').hide();
        $('#supersized').hide();
        $('#slider').hide();
        $("#thumb-list").remove();
        $("#supersized").html('');
        $('#connect').show();
        $('#navbar').show();
    });
    //Download Button in  Slideshow
    $("#btnDownload").click(function() {
        downloadAlbum(lastAlbumId);
    });
