@extends('layouts.main')
@section('css')
    <style>
        @import url(https://fonts.googleapis.com/css?family=Lato:400,700);
        /**,*/
        /**:before,*/
        /**:after {*/
        /*    box-sizing: border-box;*/
        /*}*/

        body {
            font: 14px/20px "Lato", Arial, sans-serif;
        }

        #conversation .container {
            padding: 0;
            background: #444753;
            border-radius: 5px;
            height: 80vh;
            overflow: scroll;
        }

        .people-list {
            overflow: hidden;
            height: 80vh;
            overflow: scroll;
        }

        .people-list .search {
            padding: 20px;
        }

        .people-list input {
            border-radius: 3px;
            border: none;
            padding: 14px;
            color: white;
            background: #6A6C75;
            width: 90%;
            font-size: 14px;
        }


        .people-list ul {
            padding: 0;
            padding-top: 20px;
            list-style: none;
        }

        .people-list ul li {
            cursor: pointer;
        }

        .people-list ul li:hover {
            background: #31333e;
        }

        .people-list ul .active {
            background: #31333e;
        }


        .people-list .about {
            padding-left: 8px;
        }

        .people-list .status {
            color: #92959E;
        }

        .chat {
            /* width: 490px; */
            /* float: left; */
            height: 80vh;
            overflow: scroll;
            background: #F2F5F8;
            border-top-right-radius: 5px;
            border-bottom-right-radius: 5px;
            color: #434651;
        }

        .chat .chat-header {
            padding: 20px;
            border-bottom: 2px solid white;
        }

        .chat .chat-header img {
            float: left;
        }

        .chat .chat-header .chat-about {
            float: left;
            padding-left: 10px;
            margin-top: 6px;
        }

        .chat .chat-header .chat-with {
            font-weight: bold;
            font-size: 16px;
        }

        .chat .chat-header .chat-num-messages {
            color: #92959E;
        }

        .chat .chat-header .fa-star {
            float: right;
            color: #D8DADF;
            font-size: 20px;
            margin-top: 12px;
        }

        .chat .chat-history {
            padding: 30px 30px 20px;
            border-bottom: 2px solid white;
            overflow-y: scroll;
            height: 70vh;
        }

        .chat .chat-history .message-data {
            margin-bottom: 15px;
        }

        .chat .chat-history .message-data-time {
            color: #a8aab1;
            padding-left: 6px;
        }

        .chat .chat-history ul {
            list-style: none;
            padding: 0;
        }

        .chat .chat-history .message {
            color: white;
            padding: 18px 20px;
            line-height: 26px;
            font-size: 16px;
            border-radius: 7px;
            margin-bottom: 30px;
            /*width: 90%;*/
            position: relative;
        }

        .chat .chat-history .message:after {
            bottom: 100%;
            left: 7%;
            border: solid transparent;
            content: " ";
            height: 0;
            width: 0;
            position: absolute;
            pointer-events: none;
            border-bottom-color: #86BB71;
            border-width: 10px;
            margin-left: -10px;
        }

        .chat .chat-history .my-message {
            background: #86BB71;
        }

        .chat .chat-history .other-message {
            background: #94C2ED;
        }

        .chat .chat-history .other-message:after {
            border-bottom-color: #94C2ED;
            left: 93%;
        }

        .online,
        .offline,
        .me {
            margin-right: 3px;
            font-size: 10px;
        }

        .online {
            color: #86BB71;
        }

        .offline {
            color: #9a9da6;
        }

        .me {
            color: #94C2ED;
        }

        .align-left {
            text-align: left;
        }

        .align-right {
            text-align: right;
        }

        .float-right {
            float: right;
        }

        .clearfix:after {
            visibility: hidden;
            display: block;
            font-size: 0;
            content: " ";
            clear: both;
            height: 0;
        }

    </style>
@endsection

@section('banner')
    <div class="panel-header panel-header-sm"></div>
@endsection
@section('content')
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div id="conversation">
                            <div class="container">
                                <div class="row">
                                    <div class="col-4">
                                        <div class="people-list" id="people-list">
                                            <ul class="list">
                                                @foreach($chats as $chat)
                                                    @php
                                                        $customer  = $chat->customer;
                                                        $traveller = $chat->traveler;
                                                        $unseenMessages = $chat->messages()->where('is_seen',false)->get();
                                                    @endphp

                                                    <li id="single-user-{{$chat->id}}"
                                                        class="container-fluid d-flex align-items-center justify-content-between mb-3 p-2"
                                                        onclick="fetchMessages('{{$chat->id}}',`{{$customer->avatar.','.$customer->first_name}}`, `{{$traveller->avatar.','.$traveller->first_name}}`)">
                                                        <div class="d-flex align-items-center justify-content-between">
                                                            <div class="">
                                                                <img src="{{$customer->avatar}}"  alt="" style="border: 1px solid; width: 55px; height: 55px; object-fit: cover; border-radius: 100px; position: relative; left: 40px">
                                                                <img src="{{$traveller->avatar}}"  alt="" style="border: 1px solid; width: 55px; height: 55px; object-fit: cover; border-radius: 100px; position: relative; left: 0px">
                                                            </div>
                                                            <div class="ml-3">
                                                                <p class="p-0 m-0 text-light name">
                                                                    <span>{{$customer->first_name}}</span>
                                                                     &nbsp;&nbsp; ---> &nbsp;&nbsp;
                                                                    <span>{{$traveller->first_name}}</span>
                                                                </p>
                                                                @if(count($unseenMessages) > 0)
                                                                    <small class="text-muted">{{\Illuminate\Support\Str::words($unseenMessages->last()->text, 3)}}</small>
                                                                    <br>
                                                                    <em><small class="text-muted">{{$unseenMessages->last()->created_at->diffForHumans(), 3}}</small></em>
                                                                @endif
                                                            </div>
                                                        </div>

                                                        <span class="message-counter badge badge-warning badge-pill {{count($unseenMessages) > 0 ? '' : 'd-none'}}">
                                                            {{count($unseenMessages)}}
                                                        </span>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-8">
                                        <div class="chat">
                                            <div class="chat-header clearfix d-flex align-items-center justify-content-between invisible">
                                                <div class="d-flex align-items-center">
                                                    <img id="chat-img-1" src="https://hamoodurrehman.netlify.app/files/images/bio/me.png" alt="avatar"
                                                         style="width: 55px; height: 55px; object-fit: cover; border-radius: 100px"/>
                                                    <div id="chat-about-1" class="chat-about">
                                                        <div id="chat-with-1" class="chat-with">Hamood Ur Rehman</div>
                                                    </div>
                                                </div>
                                                <div class="d-flex align-items-center">
                                                    <div  id="chat-about-2" class="chat-about mr-2">
                                                        <div  id="chat-with-2" class="chat-with">Hamood Ur Rehman</div>
                                                    </div>
                                                    <img  id="chat-img-2" src="https://hamoodurrehman.netlify.app/files/images/bio/me.png" alt="avatar"
                                                         style="width: 55px; height: 55px; object-fit: cover; border-radius: 100px"/>
                                                </div>
                                            </div> <!-- end chat-header -->

                                            <div class="chat-history">
                                                <ul id="messages">

                                                </ul>
                                            </div> <!-- end chat-history -->
                                        </div> <!-- end chat -->
                                    </div>
                                </div>
                            </div> <!-- end container -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        function fetchMessages(id, customer, traveller) {
            $('#messages').empty();

            $('.people-list li').removeClass('active');
            let selected_user = $(`#single-user-${id}`);
            $(selected_user).find('.message-counter').addClass('d-none');

            let customer_data   = customer.split(",");
            let traveller_data  = traveller.split(",");

            $('.chat-header').removeClass('invisible');

            $(`#chat-img-1`).attr("src", customer_data[0]);
            $(`#chat-with-1`).text(customer_data[1]);
            $(`#chat-img-2`).attr("src", traveller_data[0]);
            $(`#chat-with-2`).text(traveller_data[1]);

            let url = `/chat/${id}/messages`;
            $.get(url,null ,function (res, status) {
                populateConversationArea(res.data);
            });
        }

        function populateConversationArea({messages, traveller, customer}) {
            let name = null;
            messages.forEach(msg => {
                name = traveller.id === msg.user_id ? traveller.first_name + " " + traveller.last_name : customer.first_name + " " + customer.last_name;
                let msg_class = traveller.id === msg.user_id? 'other-message' : 'my-message';
                let msg_text = msg.text;

                createMessage(msg_class, name, msg_text);
            });

            scrollToBottomFunc();
        }

        function createMessage(msg_class, user_name, message) {

            let single_message = `
                        <li>
                            <div class='message-data-name ${msg_class == "other-message"? "text-right" : ""}'>
                                <i class='fa fa-circle online'></i>
                                <small>${user_name}</small>
                            </div>
                            <div class='message mt-2 ${msg_class}'>${message}</div>
                        </li>
                    `;

            $('#messages').append(single_message);
        }

        function scrollToBottomFunc() {
            $('.chat-history').animate({
                scrollTop: $('.chat-history').get(0).scrollHeight
            }, 50);
        }
    </script>

@endsection
