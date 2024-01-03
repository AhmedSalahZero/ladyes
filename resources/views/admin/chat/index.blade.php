@extends('admin.layouts.app')
@section('title','chat')
@section('content')
    <div class="app-content content">
        <div class="sidebar-left sidebar-fixed">
            <div class="sidebar">
                <div class="sidebar-content card d-none d-lg-block">
                    <div class="card-body chat-fixed-search">
                        <fieldset class="form-group position-relative has-icon-left m-0">
                            <input type="text" class="form-control" id="searchUser" placeholder="{{__('msg.search_contacts')}}">
                            <div class="form-control-position">
                                <i class="ft-search"></i>
                            </div>
                        </fieldset>
                    </div>
                    <div id="users-list" class="list-group position-relative">
                        <div class="users-list-padding media-list" id="usersList">
                            @if(count($users) > 0)
                                @foreach($users as $user)

                                    <a href="{{route('admin.chat.index',['id'=>$user->from_id,'id2'=>$user->to_id])}}"
                                       class="media border-0 {{($user->from_id == $id) && ($user->to_id == $id2) ? 'active_user' : ''}}">
                                        <div class="media-left pr-1">
                  <span class="avatar avatar-md avatar-online">
                    <img class="media-object rounded-circle"
                         src="{{isset($user->from_user->image) ? asset('public'.$user->from_user->image) : ''}}"
                         alt="Generic placeholder image">
                    <i></i>
                  </span>
                                        </div>
                                        <div class="media-body w-100">
                                            <h6 class="list-group-item-heading">{{$user->from_user->name ?? ''}} & {{$user->to_user->name ?? ''}}</h6>

                                        </div>
                                    </a>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-right">
            <div class="content-wrapper">
                <div class="content-header row">
                </div>
                <div class="content-body card">
                    @if(request()->id && request()->id > 0)
                        <section class="chat-app-window">
                            <div class="chats">
                                <div class="chats" id="chatMessages">
                                    @if(count($messages) > 0)
                                        @foreach($messages as $message)
                                            <div class="chat {{$message->from_id == request()->id ? 'chat-left' : ''}}">
                                                <div class="chat-avatar">
                                                <?php $avatar = isset($message->from_user->image) ? asset('public'.$message->from_user->image) : (isset($message->to_user->image) ? asset('public'.$message->to_user->image) : ''); ?>
                                                    <a class="avatar" data-toggle="tooltip" href="#"
                                                       data-placement="{{$message->from_id == request()->id ? 'left' : 'right'}}" title=""
                                                       data-original-title="{{$message->from_user->name}}">

                                                                <img
                                                                    src="{{$avatar}}"
                                                                    alt="{{$message->from_user->name}}"
                                                                />


                                                    </a>
                                                </div>
                                                <div class="chat-body">
                                                    <div class="chat-content">
                                                        <p>{{$message->body}}</p>
                                                        @if($message->attachment)
                                                            <?php $file = json_decode($message->attachment); ?>
                                                            <p class="file">
                                                                <a href="{{asset('storage/attachments/'.$file->new_name)}}" target="_blank">{{$file->old_name}}</a> مشاهدة المرفق
                                                            </p>
                                                        @endif
                                                        <small>{{$message->created_at->diffForHumans()}}</small>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </section>
                    @else
                        <section class="chat-app-form" style="background-color: #f4f5fa !important;">
                            <p class="alert alert-danger text-center">{{__('msg.select_user_to_chat')}}</p>
                        </section>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/pages/chat-application.css')}}">
    <style>
        .active_user{
            background-color: #32ebd0;
        }
    </style>
@endsection
@section('scripts')
    <script src="{{asset('assets/js/scripts/pages/chat-application.js')}}" type="text/javascript"></script>
    <script>

        $(document).ready(function () {
            setInterval(function () {
                $.ajax({
                    type: 'get',
                    url: "{{route('admin.chat.users')}}",
                    data: {
                        '_token': "{{csrf_token()}}",
                        'from_id' : {{$id ?? 0}},
                        'to_id' : {{$id2 ?? 0}},
                    },
                    success: function (data) {

                        if (data.status == true) {
                            $('#usersList')
                                .empty() /*remove all items*/
                                .append(data.user_html)

                        }
                    }
                });
                @if(request()->id && request()->id > 0)
                $.ajax({
                    type: 'get',
                    url: "{{route('admin.chat.user_messages',['id'=>request()->id,'id2'=> request()->id2])}}",
                    success: function (data) {

                        if (data.status == true) {
                            $('#chatMessages')
                                .empty()
                                .append(data.messages_html)
                        }
                    }
                })
                @endif
            }, 3000);
            $('#searchUser').on('input', function() {
                var searchTerm = $(this).val().toLowerCase();

                $('#usersList a').filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(searchTerm) > -1);
                });
            });
        });
    </script>
@endsection
