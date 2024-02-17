<x-app-layout>

    <div class="container mt-4">

        <div class="row">

           @if (count($users) > 0)

           <div class="col-md-3">

              <ul class="list-group">
                  @foreach ($users as $user)
                       <li class="list-group-item list-group-item-dark cursor-pointer user-list" data-id={{ $user->id }}>
                            <img src="{{ $user->image ? $user->image : asset('images/dummy-user.png') }}" alt="{{ $user->name }}" class="user-image">
                            {{ $user->name }}
                            <b><sup id="{{ $user->id }}-status" class="offline-status">Offline</sup></b>
                       </li>
                  @endforeach
              </ul>

           </div>
           <div class="col-md-9">
                   <h3 class="start-head">Click to start the chat</h3>
                   <div class="chat-section">
                        <div id="chat-container">
                            {{-- <div class="current-user-chat">
                                <h5>Hii</h5>
                            </div>
                            <div class="distance-user-chat">
                                <h5>Hello from Turkey</h5>
                            </div> --}}
                        </div>
                        <form action="" id="chat-form">
                            <input type="text" name="message" placeholder="Enter Message" id="message" class="border" required>
                            <input type="submit" value="Send Message" class="btn btn-primary">
                        </form>
                   </div>
           </div>

           @else

            <div class="col-md-12">
                <h6>Users not Found!</h6>
            </div>

           @endif

        </div>

    </div>

    {{-- data-bs-toggle="modal" data-bs-target="#deleteChatModal" --}}

  <!-- Modal Delete -->
  <div class="modal fade" id="deleteChatModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Delete Chat</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="" id="delete-chat-form">
            <div class="modal-body">
                <input type="hidden" name="id" id="delete-chat-id">
                <p>Are your sure you want to delete below Message?</p>
                <p><b id="delete-message"></b></p>
            </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-danger">Delete</button>
        </div>
    </form>
      </div>
    </div>
  </div>

  <!-- Modal Update -->

  <div class="modal fade" id="updateChatModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Update Chat</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="" id="update-chat-form">
            <div class="modal-body">
                <input type="hidden" name="id" id="update-chat-id">
                <input type="text" name="message" class="form-control" placeholder="Enter Message" required id="update-message">
            </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-success">Update</button>
        </div>
    </form>
      </div>
    </div>
  </div>
</x-app-layout>
