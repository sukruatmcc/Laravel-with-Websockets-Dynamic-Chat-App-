$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$(document).ready(function () {
    $('.user-list').click(function () {

        $('#chat-container').html('');

        var getUserId = $(this).attr('data-id');

        receiver_id = getUserId;

        $('.start-head').hide();
        $('.chat-section').show();

        loadOldChats();

    })

    //save chat work

    $('#chat-form').submit(function (e) {
        e.preventDefault();

        var message = $('#message').val();

        $.ajax({
            url: "/save-chat",
            type: "POST",
            data: { sender_id: sender_id, receiver_id: receiver_id, message: message, },
            success: function (response) {
                console.log(response)
                if (response.success) {
                    $('#message').val('');//->mesaj gönderimi success olduğunda value sıfırlama
                    let chat = response.data.message;
                    let html = `
                     <div class="current-user-chat" id:'`+ response.data.id + `-chat'>
                         <h5>
                            <span>`+ chat + `</span>
                            <button type="button" data-id='`+ response.data.id + `' class="btn btn-danger delete" data-bs-toggle="modal" data-bs-target="#deleteChatModal">
                            <i class="fas fa-trash" data-id='`+ response.data.id + `' class="btn btn-danger delete" data-bs-toggle="modal" data-bs-target="#deleteChatModal"></i>
                         </button>
                         <i class="fas fa-edit" data-id='`+ response.data.id + `' data-msg='`+response.data.message+`'  data-bs-toggle="modal" data-bs-target="#updateChatModal"></i>
                         </h5>
                     </div>
                     `;
                    $('#chat-container').append(html);
                    scrollChat();
                } else {
                    alert(response.msg)
                }
            }
        })
    })

    //delete chat message

    $(document).on('click', '.delete', function () {
        var id = $(this).attr('data-id');
        var test = $('#delete-chat-id').val(id);
        $('#delete-message').text($(this).parent().text());
    });


    $('#delete-chat-form').submit(function (e) {
        e.preventDefault();

        var id = $('#delete-chat-id').val();
        $.ajax({
            url: '/delete-chat/',
            type: 'POST',
            data: { id: id },
            success: function (res) {
                 if (res.success) {
                     $('#' + id + '-chat').remove();
                     $('#deleteChatModal').modal('hide');
                 }
            }
        });

    });
});

//loadOldChats->eski mesajları yükleme

function loadOldChats() {
    $.ajax({
        url: "/load-chats",
        type: "POST",
        data: { sender_id: sender_id, receiver_id: receiver_id },
        success: function (res) {
            console.log(res)
            if (res.success) {
                let chats = res.data;
                let html = '';
                for (let i = 0; i < chats.length; i++) {
                    console.log('Length = ' + chats.length)
                    let addClass = '';
                    console.log('chat[i] = ' + chats[i].sender_id + '  ' + 'sender_id = ' + sender_id)
                    if (chats[i].sender_id == sender_id) {
                        addClass = 'current-user-chat'
                    }
                    else {
                        addClass = 'distance-user-chat'
                    }
                    html += `
                 <div class="`+ addClass + `" id='` + chats[i].id + `-chat'>
                    <h5>
                        <span>`+ chats[i].message + `</span>`;
                    if (chats[i].sender_id == sender_id) {
                        html += `
                            <button type="button" data-id='`+ chats[i].id + `' class="btn btn-danger delete" data-bs-toggle="modal" data-bs-target="#deleteChatModal">
                            <i class="fas fa-trash"></i>
                           </button>
                           <i class="fas fa-edit" data-id='`+ chats[i].id + `' data-msg='`+chats[i].message+`'  data-bs-toggle="modal" data-bs-target="#updateChatModal"></i>
                            `;
                    }
                    html += `
                    </h5>
                </div>
                 `;
                }
                $('#chat-container').append(html);
                scrollChat();
                // data-bs-toggle="modal" data-bs-target="#deleteChatModal
            } else {
                alert(res.msg)
            }
        }
    });
}

//scroll div
function scrollChat() {
    $('#chat-container').animate({
        scrollTop: $('#chat-container').offset().top + $('#chat-container')[0].scrollHeight
    }, 0)
}

Echo.join('status-update')
    .here((users) => {
        for (let x = 0; x < users.length; x++) {
            if (sender_id != users[x]['id']) {
                $('#' + users[x]['id'] + '-status').removeClass('offline-status');
                $('#' + users[x]['id'] + '-status').addClass('online-status');
                $('#' + users[x]['id'] + '-status').text('Online');
            }
        }
    })
    .joining((user) => {
        $('#' + user.id + '-status').removeClass('offline-status')
        $('#' + user.id + '-status').addClass('online-status')
        $('#' + user.id + '-status').text('Online')
    })
    .leaving((user) => {
        $('#' + user.id + '-status').addClass('offline-status')
        $('#' + user.id + '-status').removeClass('online-status')
        $('#' + user.id + '-status').text('Offline')
    })
    .listen('UserStatusEvent', (e) => {
        console.log(e);
    });

Echo.private('broadcast-message')
    .listen('.getChatMessage', (data) => {
        console.log(data)

        if (sender_id == data.chat.receiver_id && receiver_id == data.chat.sender_id) {
            let html = `
                <div class="distance-user-chat" id='`+ data.chat.id + `-chat'>
                <h5><span>`+ data.chat.message + `</span></h5>
                </div>
            `;

            $('#chat-container').append(html);
            scrollChat();
        }
});

//delete chat message listen
Echo.private('message-deleted')
    .listen('MessageDeletedEvent', (data) => {
        $('#' + data.id + '-chat').remove();
});

//update chat message

$(document).on('click','.fa-edit',function(){

    $('#update-chat-id').val($(this).attr('data-id'));
    $('#update-message').val($(this).attr('data-msg'));

});

$(document).ready(function(){
    $('#update-chat-form').submit(function(e){
        e.preventDefault();

        var id = $('#update-chat-id').val();
        var msg = $('#update-message').val();

        $.ajax({
           url:"/update-chat",
           type:"POST",
           data:{id:id,message:msg},
           success:function(res){
               if(res.success){
                  $('#updateChatModal').modal('hide');
                  $('#'+id+'-chat').find('span').text(msg);
                  $('#'+id+'-chat').find('.fa-edit').attr('data-msg',msg);
               }else{
                   alert(res.msg)
               }
           }
        });
   });
});

Echo.private('message-updated')
.listen('MessageUpdatedEvent',(data) => {
    //  console.log(data)
    $('#'+data.data.id+'-chat').find('span').text(data.data.message);
});


