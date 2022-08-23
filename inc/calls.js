function makeLink(li){
    $('#new_path').val(li.html());
    $('#new_notify').val(1);
    $('#new_exp').val(0);
    $('#new_pw').val(0);
    $('#new_exp_date').val('');
    $('#new_password').val('');
    $('#confirm_path').val('');
    $('#confirm_pw').val('');
    $('#confirm_exp').val('');
    $('#short_link').val('');
    $('#short').hide();
    $('#new_exp_date').prop( "disabled", true );
    $('#new_password').prop( "disabled", true );
    $('#new_modal').modal('show');
}

function checkInput(context,type){
    if (context == 'new') {
        var x = type == 'exp' ? $('#new_exp') : $('#new_pw'); 
        var y = type == 'exp' ? $('#new_exp_date') : $('#new_password'); 
        if (x.val() == '1'){
            y.prop( "disabled", false );
        }   else    {
            y.prop( "disabled", true );
        }
    }   else    {
        var x = type == 'exp' ? $('#edit_exp') : $('#edit_pw'); 
        var y = type == 'exp' ? $('#edit_exp_date') : $('#edit_password'); 
        if (x.val() == '1'){
            y.prop( "disabled", false );
        }   else    {
            y.prop( "disabled", true );
        }
    }    
}

function shortenLink(context){
    if (context == 'new'){
        $('#short').show();
        $.post('/inc/calls.php', {type: 'shorten', guid : active_guid, url : $('#confirm_path').val()}, function(data){
            var x  = JSON.parse(data);
            if (x.success){
                $('#short_link').val(x.link);
                $('#new_short').hide();
            } 
        });        
    }   else    {
        $('#short_edit').show();
        $.post('/inc/calls.php', {type: 'shorten', guid : active_guid, url : $('#edit_url').val()}, function(data){
            var x  = JSON.parse(data);
            if (x.success){
                $('#short_link_edit').val(x.link);
                $('#edit_shorten_button').hide();
            } 
        });    
    }

}

function drawDownload(){
    $('#downloads_table_list').html('');
    $.get('/inc/calls.php?type=downloads', function(data){
        x = JSON.parse(data);
        $.each(x, function(i,b){
            $('#downloads_table_list').append(`
            <tr>
                <td>`+b.path+`</td>
                <td>`+b.ip_address+`</td>
                <td>`+b.dt_eng+`</td>
            </tr>
            `);            
        });

    });
}

function newLink(){
    $.post('/inc/calls.php', {
        type : 'new',
        path : $('#new_path').val(),
        new_notify : $('#new_notify').val(),
        new_exp : $('#new_exp').val(),
        new_exp_date : ($('#new_exp').val() == 1 ? $('#new_exp_date').val() : null),
        new_pw : $('#new_pw').val(),
        new_password : $('#new_password').val()
    }, function(data){
        var x = JSON.parse(data);
        if (x.success){
            $('#confirm_path').val(document.location.origin+'/?guid='+x.guid);
            $('#confirm_pw').val(x.password);
            $('#confirm_exp').val(x.expires); 
            active_guid = x.guid;
        }
    });
    $('#new_modal').modal('hide');
    $('#success_modal').modal('show');
}

function copyText(id) {
    var x = id.attr('id');
    var copyText = document.getElementById(x);
    copyText.select();
    copyText.setSelectionRange(0, 99999); /* For mobile devices */
    navigator.clipboard.writeText(copyText.value);
}

function menuClick(me){
    $('.page-item').each(function(){$(this).removeClass('active')}).promise().done(() => me.addClass('active'));
    $('.window').each(function(){$(this).hide()}).promise().done(() => {
        switch (me.attr('id')){
            case 'menu_browser':
                $('#browser').show();    
                break;
            case 'menu_link':
                drawLinks();
                $('#links').show();
                break;
            case 'menu_downloads':
                drawDownload();
                $('#downloads').show();
                break;
            case 'menu_settings':
                $('#settings').show();
                break;
        }
    });
}

function drawLinks(){
    $('#links_table_list').html('');
    $.get('/inc/calls.php?type=links', function(data){
        $.each(JSON.parse(data), function(i, b){
            $('#links_table_list').append(`
                <tr class="oldlink">
                <td> `+(b.filename.length > 19 ? b.filename.substring(0, 17)+'...' : b.filename)+`</td>
                <td><input type="text" class="form-control oldlink" id="url`+b.id+`" readonly value="`+b.url+`" onclick="copyText($(this))"></td>
                <td>`+b.short_dt+`</td>
                <td>`+(b.protect == 1 ? '✔️' : '')+`</td>
                <td>`+b.short_exp+`</td>
                <td>`+(b.notify == 1 ? '✔️' : '')+`</td>
                <td>`+(b.downloads == 0 ? "" : b.downloads)+`</td>
                <td class="link_bar">`+(b.bitly_url == null ? '' : `<input type="text" style="width: 200px;" class="form-control oldlink" id="b_url`+b.id+`" readonly value="`+b.bitly_url+`" onclick="copyText($(this))">`)+`</td>
                <td class="edit_buttons"><span class="del_button"> <font style="margin-right: 15px;" onclick="loadLink('`+b.guid+`')">✏️</font> <font onclick="deleteLink('`+b.guid+`','`+b.filename+`')">`+`🚫</font></span></td>
                </tr>
            `);
        });

    });
}

function drawBitlyLinks(){
    $('#bitly_table_list').html('');
    $.get('/inc/calls.php?type=bitly', function(data){
        $.each(JSON.parse(data), function(i, b){
            $('#bitly_table_list').append(`
                <tr class="oldlink active_bitlinks">
                    <td class="link_bar"><input type="text" style="width: 200px;" class="form-control oldlink" id="b_url`+b.id+`" readonly value="`+b.bitly_url+`" onclick="copyText($(this))"></td>
                    <td> `+b.path+`</td>
                    <td>`+(b.downloads == 0 ? "" : b.downloads)+`</td>
                </tr>
            `);
        });
    });
}

function deleteLink(guid,filename){
    x = confirm('Are you sure you want to delete the link for '+filename+'.');
    if (x){
        $.post('/inc/calls.php', {type : 'delete', 'guid' : guid}, function (data){}).then(() => {
            drawLinks();
        });
    }
}

function syncLabels(modal){
    var w = [];
    $.each($('#'+modal+' label'), function(){
        w.push($(this).width())        
    });
    width = Math.max.apply(Math,w)    
    $.each($('#'+modal+' label'), function(){
        $(this).width(width);
    });
}

function loadLink(guid){
    $('#edit_modal').modal('show');
    $.get('/inc/calls.php?type=lookup&guid='+guid, function(data){
        x = JSON.parse(data);
        $('#edit_url').val(x.url);
        $('#edit_path').val(x.file_path);
        $('#edit_notify').val((x.notify ? 1 : 0));
        $('#edit_exp').val((x.expiration == null ? 0 : 1));
        $('#edit_exp_date').val(x.expiration);
        $('#edit_pw').val((x.password == null ? 0 : 1));
        $('#edit_password').val(x.password);
        if (x.bitly_url == null){
            $('#short_edit').hide();
            $('#short_link_edit').val('');
            $('#edit_shorten_button').show();
        }   else    {
            $('#short_edit').show();
            $('#short_link_edit').val(x.bitly_url);
            $('#edit_shorten_button').hide();
        }
        active_guid = x.guid;
    });
}

function loadSettings(){
    $.get('/inc/calls.php?type=settings', function(data){
        x = JSON.parse(data);
        $('#settings_protocol').val(x.protocol_type);
        $('#settings_bitly').val(x.use_bitly ? '1' : '0');
        $('#settings_bitly_token').val(x.bitly_token);
        $('#settings_email').val(x.use_email ? '1' : '0');
        $('#settings_email_recip').val(x.email_notification);
        $('#settings_email_sender').val(x.smtp_from_address);
        $('#settings_email_server').val(x.smtp_server);
        $('#settings_email_port').val(x.smtp_port);
        $('#settings_email_login').val(x.smtp_security ? '1' : '0');
        $('#settings_email_user').val(x.smtp_username);
        $('#settings_email_pass').val(x.smtp_password);
        $('#settings_email_security').val(x.smtp_security_type);
    }).then(() => {
        settingsCheck();
    });
}

function editLink(){
    $.post('/inc/calls.php', {
        type : 'edit',
        guid : active_guid,
        edit_notify : $('#edit_notify').val(),
        edit_exp : $('#edit_exp').val(),
        edit_exp_date : ($('#edit_exp').val() == 1 ? $('#edit_exp_date').val() : null),
        edit_pw : $('#edit_pw').val(),
        edit_password : $('#edit_password').val()
    }, function(data){
        drawLinks();
        $('#edit_modal').modal('hide');
    });
}

function settingsCheck(){
    if ($('#settings_bitly').val() == 1){
        $.each($('.bitly_settings'), function(){$(this).show();});
    }   else    {
        $.each($('.bitly_settings'), function(){$(this).hide();});
    }

    if ($('#settings_email').val() == 1){
        $.each($('.email_settings'), function(){$(this).show();});
    }   else    {
        $.each($('.email_settings'), function(){$(this).hide();});
    }

    if ($('#settings_email_login').val() == 1 && $('#settings_email').val() == 1){
        $.each($('.email_login'), function(){$(this).show();});
    }   else    {
        $.each($('.email_login'), function(){$(this).hide();});
    }
}

function editSettings(){
    var sata = $("#settings_form").serialize()+'&type=settings';
    $.post('/inc/calls.php', sata, function(data){
        console.log();
    });
}

function logout(){
    $.post('./inc/calls.php',{type: 'logout'}).then(() => {
        window.location.href = "admin.php";
    });
}

function updatePassword(){
    var change = false;
    $('#change_title').html();
    if ($('#new_password1').val() != $('#new_password2').val()){
        $('#change_title').html(' Passwords Do Not Match');
    }   else if ($('#new_password1').val().length  < 8)    {
        $('#change_title').html(' New Password Too Short (Min. 8 Characters)');
    }   else    {
        $.post('./inc/calls.php',{type: 'password', old_password: $('#old_password').val(),new_password: $('#new_password1').val()}, function(data){
            x = JSON.parse(data);
            change = x.success;
        }).then(() => {
            if (change){
                logout();
            }   else    {
                if (x.message == "new and old passwords match"){
                    $('#change_title').html(' New and Previous Passwords Cannot Be The Same');
                }   else if (x.message == "invalid password"){
                    $('#change_title').html(' Current Password Invalid');
                }
            }
        });
    }
}

function updateUsername(){
    var change = false;
    $('#user_title').html();
    if ($('#new_username').val().length < 5){
        $('#user_title').html(' New Username Too Short (Min. 5 Characters)');
    }   else    {
        $.post('./inc/calls.php',{type: 'username', old_password: $('#old_password').val(),new_username: $('#new_username').val()}, function(data){
            x = JSON.parse(data);
            change = x.success;
        }).then(() => {
            if (change){
                logout();
            }   else    {
                if (x.message == "invalid password"){
                    $('#user_title').html(' Password Invalid');
                }   else if (x.message == "usernames match"){
                    $('#user_title').html(' New and Previous Usernames Cannot Be The Same');
                }
            }
        });
    }
}

function testEmail(){
    $.post('./inc/calls.php',{
        type: 'test_email',
        smtp_server: $('#settings_email_server').val(),
        smtp_port: $('#smtp_port').val(),
        smtp_security: $('#settings_email_login').val(),
        smtp_username: $('#settings_email_user').val(),
        smtp_password: $('#settings_email_pass').val(),
        smtp_security_type: $('#settings_email_security').val(),
        email_notification: $('#settings_email_recip').val(),
        smtp_from_address: $('#settings_email_sender').val()        
    }, function(data){
        var x  = JSON.parse(data);
        if (x.success){
            alert('Message Successfully Sent.');
        }   else    {
            alert("Error Sending Message.\r\nError Message: "+x.message);
        }
    });
}

function testBitly(){
    $.post('./inc/calls.php',{type: 'test_bitly', apikey: $('#settings_bitly_token').val()}, function(data){
        alert('Test to Bitly was '+(data == "1" ? "successful." : "unsuccessful."));
    });
}