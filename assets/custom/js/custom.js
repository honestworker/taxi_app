$(function () {
    /*
     * Admins Page
     */
    // Datatable
    $('#admin_table').DataTable({
        'paging'      : true,
        'lengthChange': true,
        'searching'   : true,
        'ordering'    : true,
        'info'        : true,
        'autoWidth'   : true,
        'scrollY'     : true,
        'order'       : [[ 3, "desc" ]]
    });

    /*
     * Drivers Page
     */
    // Datatable
    $('#driver_table').DataTable({
        'paging'      : true,
        'lengthChange': true,
        'searching'   : true,
        'ordering'    : true,
        'info'        : true,
        'autoWidth'   : true,
        'scrollY'     : true,
        'order'       : [[ 7, "desc" ]]
    });

    /*
     * Users Page
     */
    // Datatable
    $('#user_table').DataTable({
        'paging'      : true,
        'lengthChange': true,
        'searching'   : true,
        'ordering'    : true,
        'info'        : true,
        'autoWidth'   : true,
        'scrollY'     : true,
        'order'       : [[ 4, "desc" ]]
    });

    /*
     * Advertisement Page
     */
    // Datatable
    $('#ad_table').DataTable({
        'paging'      : true,
        'lengthChange': true,
        'searching'   : true,
        'ordering'    : true,
        'info'        : true,
        'autoWidth'   : true,
        'scrollY'     : true,
        'order'       : [[ 3, "desc" ]]
    });

    function handleFileSelect(evt) {
        var files = evt.target.files; // FileList object
        
        // Loop through the FileList and render image files as thumbnails.
        for (var i = 0, f; f = files[i]; i++) {
            // Only process image files.
            if (!f.type.match('image.*')) {
                continue;
            }
            
            var reader = new FileReader();
            
            // Closure to capture the file information.
            reader.onload = (function(theFile) {
                return function(e) {
                    // Render thumbnail.
                    var span = document.createElement('span');
                    span.innerHTML = ['<img class="thumb" src="', e.target.result, '" title="', escape(theFile.name), '"/>'].join('');
                    var listNode = document.getElementById('list');
                    while (listNode.firstChild) {
                        listNode.removeChild(listNode.firstChild);
                    }
                    document.getElementById('list').append(span);
                };
            })(f);
            
            // Read in the image file as a data URL.
            reader.readAsDataURL(f);
        }
    }

    document.getElementById('images').addEventListener('change', handleFileSelect, false);
});

/*
 * User Management
*/
var user_id = 0;

function viewActionUserModal(url, action, id) {
    user_id = id;
    $('#' + url + '-' + action + '-modal').modal('show');
}

function actionUser(url, action) {
    $.ajax({
        type: 'GET',
        url: "../users/" + action + "/" + user_id,
        success: function(resposne) {
            var data = JSON.parse(resposne);
            console.log(data);
            if ( data.status == 'success' ) {
                window.location = $('#base_url').val() + url + 's';
            } else {
                setTimeout(function() {
                    $.bootstrapGrowl(data.message, {
                        type: 'danger',
                        allow_dismiss: true
                    });
                }, 1000);
            }
            $('#' + url + '-' + action + '-modal').modal('hide');
        },
        error: function(data) {
            setTimeout(function() {
                $.bootstrapGrowl('Server Error', {
                    type: 'danger',
                    allow_dismiss: true
                });
            }, 1000);
            $('#' + url + '-' + action + '-modal').modal('hide');
        }
    });
}

/*
 * User Management
*/
var ad_id = 0;

function viewActionAdModal(url, action, id) {
    ad_id = id;
    $('#' + url + '-' + action + '-modal').modal('show');
}

function actionAd(url, action) {
    $.ajax({
        type: 'GET',
        url: "../ads/" + action + "/" + ad_id,
        success: function(resposne) {
            var data = JSON.parse(resposne);
            console.log(data);
            if ( data.status == 'success' ) {
                window.location = $('#base_url').val() + url + 's';
            } else {
                setTimeout(function() {
                    $.bootstrapGrowl(data.message, {
                        type: 'danger',
                        allow_dismiss: true
                    });
                }, 1000);
            }
            $('#' + url + '-' + action + '-modal').modal('hide');
        },
        error: function(data) {
            setTimeout(function() {
                $.bootstrapGrowl('Server Error', {
                    type: 'danger',
                    allow_dismiss: true
                });
            }, 1000);
            $('#' + url + '-' + action + '-modal').modal('hide');
        }
    });
}