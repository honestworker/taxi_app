$(function () {
    /*
     * Admins Page
     */
    $admin_id = 0;

    // Datatable
    $('#admin_table').DataTable({
      'paging'      : true,
      'lengthChange': true,
      'searching'   : true,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : true
    });

    // Modal
    $('.admin-table .btn-success').click(function() {
        $admin_id = $(this).parent().parent().data('id');
        $('#admin-active-modal').modal('show');
    });

    $('.admin-table .btn-warning').click(function() {
        $admin_id = $(this).parent().parent().data('id');
        $('#admin-disable-modal').modal('show');
    });

    $('.admin-table .btn-danger').click(function() {
        $admin_id = $(this).parent().parent().data('id');
        $('#admin-delete-modal').modal('show');
    });
    
    // Button
    $('.active-admin').click(function() {
        $.ajax({
            type: 'GET',
            url: "../users/active/" + $admin_id,
            success: function(resposne) {
                var data = JSON.parse(resposne);
                console.log(data);
                if ( data.status == 'success' ) {
                    window.location = $('#base_url').val() + 'admins';
                }
                $('#driver-active-modal').modal('hide');
            },
            error: function(data) {
                setTimeout(function() {
                    $.bootstrapGrowl('Server Error', {
                        type: 'danger',
                        allow_dismiss: true
                    });
                }, 1000);
                $('#driver-active-modal').modal('hide');
            }
        });
    });

    $('.disable-admin').click(function() {
        $.ajax({
            type: 'GET',
            url: "../users/disable/" + $admin_id,
            success: function(resposne) {
                var data = JSON.parse(resposne);
                console.log(data);
                if ( data.status == 'success' ) {
                    window.location = $('#base_url').val() + 'admins';
                }
                $('#driver-active-modal').modal('hide');
            },
            error: function(data) {
                setTimeout(function() {
                    $.bootstrapGrowl('Server Error', {
                        type: 'danger',
                        allow_dismiss: true
                    });
                }, 1000);
                $('#driver-active-modal').modal('hide');
            }
        });
    });

    $('.delete-admin').click(function() {
        $.ajax({
            type: 'GET',
            url: "../users/delete/" + $admin_id,
            success: function(resposne) {
                var data = JSON.parse(resposne);
                console.log(data);
                if ( data.status == 'success' ) {
                    window.location = $('#base_url').val() + 'admins';
                }
                $('#driver-active-modal').modal('hide');
            },
            error: function(data) {
                setTimeout(function() {
                    $.bootstrapGrowl('Server Error', {
                        type: 'danger',
                        allow_dismiss: true
                    });
                }, 1000);
                $('#driver-active-modal').modal('hide');
            }
        });
    });
    
    /*
     * Drivers Page
     */
    $driver_id = 0;

    // Datatable
    $('#driver_table').DataTable({
      'paging'      : true,
      'lengthChange': true,
      'searching'   : true,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : true
    });

    // Modal
    $('.driver-table .btn-success').click(function() {
        $driver_id = $(this).parent().parent().data('id');
        $('#driver-active-modal').modal('show');
    });

    $('.driver-table .btn-warning').click(function() {
        $driver_id = $(this).parent().parent().data('id');
        $('#driver-disable-modal').modal('show');
    });

    $('.driver-table .btn-danger').click(function() {
        $driver_id = $(this).parent().parent().data('id');
        $('#driver-delete-modal').modal('show');
    });
    
    // Button
    $('.active-driver').click(function() {
        $.ajax({
            type: 'GET',
            url: "../users/active/" + $driver_id,
            success: function(resposne) {
                var data = JSON.parse(resposne);
                console.log(data);
                if ( data.status == 'success' ) {
                    window.location = $('#base_url').val() + 'drivers';
                }
                $('#driver-active-modal').modal('hide');
            },
            error: function(data) {
                setTimeout(function() {
                    $.bootstrapGrowl('Server Error', {
                        type: 'danger',
                        allow_dismiss: true
                    });
                }, 1000);
                $('#driver-active-modal').modal('hide');
            }
        });
    });

    $('.disable-driver').click(function() {
        $.ajax({
            type: 'GET',
            url: "../users/disable/" + $driver_id,
            success: function(resposne) {
                var data = JSON.parse(resposne);
                console.log(data);
                if ( data.status == 'success' ) {
                    window.location = $('#base_url').val() + 'drivers';
                }
                $('#driver-disable-modal').modal('hide');
            },
            error: function(data) {
                setTimeout(function() {
                    $.bootstrapGrowl('Server Error', {
                        type: 'danger',
                        allow_dismiss: true
                    });
                }, 1000);
                $('#driver-disable-modal').modal('hide');
            }
        });
    });

    $('.delete-driver').click(function() {
        $.ajax({
            type: 'GET',
            url: "../users/delete/" + $driver_id,
            success: function(resposne) {
                var data = JSON.parse(resposne);
                console.log(data);
                if ( data.status == 'success' ) {
                    window.location = $('#base_url').val() + 'drivers';
                }
                $('#driver-delete-modal').modal('hide');
            },
            error: function(data) {
                setTimeout(function() {
                    $.bootstrapGrowl('Server Error', {
                        type: 'danger',
                        allow_dismiss: true
                    });
                }, 1000);
                $('#driver-delete-modal').modal('hide');
            }
        });
    });
    
    /*
     * Users Page
     */
    $user_id = 0;

    // Datatable
    $('#user_table').DataTable({
      'paging'      : true,
      'lengthChange': true,
      'searching'   : true,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : true
    });

    // Modal
    $('.user-table .btn-success').click(function() {
        $user_id = $(this).parent().parent().data('id');
        $('#user-active-modal').modal('show');
    });

    $('.user-table .btn-warning').click(function() {
        $user_id = $(this).parent().parent().data('id');
        $('#user-disable-modal').modal('show');
    });

    $('.user-table .btn-danger').click(function() {
        $user_id = $(this).parent().parent().data('id');
        $('#user-delete-modal').modal('show');
    });
    
    // Button
    $('.active-user').click(function() {
        $.ajax({
            type: 'GET',
            url: "../users/active/" + $user_id,
            success: function(resposne) {
                var data = JSON.parse(resposne);
                console.log(data);
                if ( data.status == 'success' ) {
                    window.location = $('#base_url').val() + 'users';
                }
                $('#user-active-modal').modal('hide');
            },
            error: function(data) {
                setTimeout(function() {
                    $.bootstrapGrowl('Server Error', {
                        type: 'danger',
                        allow_dismiss: true
                    });
                }, 1000);
                $('#user-active-modal').modal('hide');
            }
        });
    });

    $('.disable-user').click(function() {
        $.ajax({
            type: 'GET',
            url: "../users/disable/" + $user_id,
            success: function(resposne) {
                var data = JSON.parse(resposne);
                console.log(data);
                if ( data.status == 'success' ) {
                    window.location = $('#base_url').val() + 'users';
                }
                $('#user-disable-modal').modal('hide');
            },
            error: function(data) {
                setTimeout(function() {
                    $.bootstrapGrowl('Server Error', {
                        type: 'danger',
                        allow_dismiss: true
                    });
                }, 1000);
                $('#user-disable-modal').modal('hide');
            }
        });
    });

    $('.delete-user').click(function() {
        $.ajax({
            type: 'GET',
            url: "../users/delete/" + $user_id,
            success: function(resposne) {
                var data = JSON.parse(resposne);
                console.log(data);
                if ( data.status == 'success' ) {
                    window.location = $('#base_url').val() + 'users';
                }
                $('#user-delete-modal').modal('hide');
            },
            error: function(data) {
                setTimeout(function() {
                    $.bootstrapGrowl('Server Error', {
                        type: 'danger',
                        allow_dismiss: true
                    });
                }, 1000);
                $('#user-delete-modal').modal('hide');
            }
        });
    });
    
    /*
     * Advertisement Page
     */
    $ad_id = 0;

    // Datatable
    $('#ad_table').DataTable({
      'paging'      : true,
      'lengthChange': true,
      'searching'   : true,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : true
    });

    // Modal
    $('.ad-table .btn-success').click(function() {
        $ad_id = $(this).parent().parent().data('id');
        $('#ad-active-modal').modal('show');
    });

    $('.ad-table .btn-warning').click(function() {
        $ad_id = $(this).parent().parent().data('id');
        $('#ad-disable-modal').modal('show');
    });

    $('.ad-table .btn-danger').click(function() {
        $ad_id = $(this).parent().parent().data('id');
        $('#ad-delete-modal').modal('show');
    });
    
    // Button
    $('.active-ad').click(function() {
        $.ajax({
            type: 'GET',
            url: "../ads/active/" + $ad_id,
            success: function(resposne) {
                var data = JSON.parse(resposne);
                console.log(data);
                if ( data.status == 'success' ) {
                    window.location = $('#base_url').val() + 'ads';
                }
                $('#ad-active-modal').modal('hide');
            },
            error: function(data) {
                setTimeout(function() {
                    $.bootstrapGrowl('Server Error', {
                        type: 'danger',
                        allow_dismiss: true
                    });
                }, 1000);
                $('#ad-active-modal').modal('hide');
            }
        });
    });

    $('.disable-ad').click(function() {
        $.ajax({
            type: 'GET',
            url: "../ads/disable/" + $ad_id,
            success: function(resposne) {
                var data = JSON.parse(resposne);
                console.log(data);
                if ( data.status == 'success' ) {
                    window.location = $('#base_url').val() + 'ads';
                }
                $('#ad-disable-modal').modal('hide');
            },
            error: function(data) {
                setTimeout(function() {
                    $.bootstrapGrowl('Server Error', {
                        type: 'danger',
                        allow_dismiss: true
                    });
                }, 1000);
                $('#ad-disable-modal').modal('hide');
            }
        });
    });

    $('.delete-ad').click(function() {
        $.ajax({
            type: 'GET',
            url: "../ads/delete/" + $ad_id,
            success: function(resposne) {
                var data = JSON.parse(resposne);
                console.log(data);
                if ( data.status == 'success' ) {
                    window.location = $('#base_url').val() + 'ads';
                }
                $('#ad-delete-modal').modal('hide');
            },
            error: function(data) {
                setTimeout(function() {
                    $.bootstrapGrowl('Server Error', {
                        type: 'danger',
                        allow_dismiss: true
                    });
                }, 1000);
                $('#ad-delete-modal').modal('hide');
            }
        });
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
})