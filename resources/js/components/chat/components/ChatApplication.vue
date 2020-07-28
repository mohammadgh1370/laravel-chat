<template>
    <div class="card direct-chat direct-chat-primary" ref="chatbox">
        <div class="card-header">
            <h3 class="card-title">گفتگو</h3>

            <div class="card-tools">
    <span data-toggle="tooltip" title="پیام های خوانده نشده"
          class="badge badge-primary" v-show="unreadMessage > 0">{{digit(unreadMessage)}}</span>
                <button type="button" class="btn btn-tool" data-widget="collapse">
                    <i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-tool" data-toggle="tooltip" title="آنلاین"
                        data-widget="chat-pane-toggle">
                    <i class="fa fa-comments"></i>
                    <span class="badge badge-warning">{{digit(online)}}</span>
                </button>
                <button type="button" class="btn btn-tool" data-widget="remove"><i
                        class="fa fa-times"></i>
                </button>
            </div>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <!-- Conversations are loaded here -->
            <div class="direct-chat-messages" ref="messagebox">

                <div v-show="chatOpen && !loadingMessages">
                    <!-- Message. Default to the left -->
                    <div class="direct-chat-msg" v-for="message in messages"
                         :class="{'right': message.sender_id !== receiverUserId}">
                        <div class="direct-chat-info clearfix">
                            <span class="direct-chat-name float-left">{{message.sender.name}}</span>
                            <span class="direct-chat-timestamp float-right">
                                <!--{{digit(message.created_at)}}-->
                                <timeago :datetime="message.created_at" :auto-update="60"></timeago>
                            </span>
                        </div>
                        <!-- /.direct-chat-info -->
                        <img class="direct-chat-img" :src="message.sender.avatar"
                             alt="message user image">
                        <!-- /.direct-chat-img -->
                        <div class="direct-chat-text">
                            {{message.body}}
                        </div>
                        <!-- /.direct-chat-text -->
                    </div>
                    <!-- /.direct-chat-msg -->
                </div>
            </div>
            <!--/.direct-chat-messages-->

            <!-- Contacts are loaded here -->
            <div class="direct-chat-contacts">
                <ul class="contacts-list pointer" v-for="user in users">
                    <li>
                        <div @click="openChat(user)">
                            <img class="contacts-list-img"
                                 :src="user.avatar">

                            <div class="contacts-list-info">
                                <span class="contacts-list-name"
                                      :class="{'font-weight-bold': receiverUserId === user.id}">
                                {{user.name}}
                                <small class="contacts-list-date float-left">
                                    <!--{{digit(user.created_at)}}-->
                                <timeago :datetime="user.created_at" :auto-update="60"></timeago>
                                </small>
                                </span>
                                <span class="contacts-list-msg">{{user.message}}</span>
                                <span class="badge badge-info" v-show="user.unread > 0">{{digit(user.unread)}}</span>
                            </div>
                            <!-- /.contacts-list-info -->
                        </div>
                    </li>
                </ul>
                <!-- /.contacts-list -->
            </div>
            <!-- /.direct-chat-pane -->
        </div>
        <!-- /.card-body -->
        <div class="card-footer">
            <div class="input-group">
                <input type="text" name="message" placeholder="پیام خود را بنویسید ..."
                       class="form-control" v-model="body" @keyup.enter="sendMessage"
                       :disabled="!chatOpen && !loadingMessages">
                <span class="input-group-append">
                    <button type="button" class="btn btn-primary" @click="sendMessage"
                            :disabled="!chatOpen && !loadingMessages">ارسال</button>
                </span>
            </div>
        </div>
        <!-- /.card-footer-->
    </div>
</template>

<script>
    import {EventBus} from '../../../event';
    import persianDigit from '../../../persianDigit';

    export default {
        name: 'ChatApplication',
        data: () => {
            return {
                users: [],
                messages: [],
                online: 0,
                unreadMessage: 0,
                body: '',
                receiverUser: '',
                senderUser: '',
                receiverUserId: null,
                chatOpen: false,
                loadingMessages: false,
            }
        },
        updated() {
            this.scroll();
        },
        created() {
//            this.loadUser();
//            this.online();
        },
        mounted() {
            EventBus.$on('users_join', this.usersJoin);
            EventBus.$on('user_join', this.userJoin);
            EventBus.$on('user_left', this.userLeft);
            EventBus.$on('chat-' + window.Laravel.user.id, this.listenMessage);
        },
        methods: {
            openChat(user) {
                this.$refs.chatbox.classList.remove('direct-chat-contacts-open');
                let app = this;
                if (app.receiverUserId !== user.id) {
                    app.receiverUserId = user.id;
                    app.chatOpen = true;
                    if (user.uread !== 0) {
                        app.unreadMessage = app.unreadMessage - user.unread;
                        user.uread = 0;
                    }
//                    // Start socket.io listener
//                    window.Echo.channel('newMessage-*-' + app.$root.userID)
//                        .listen('.message_send', (data) => {
////                    if (app.receiverUserId) {
//                            app.messages.push(data.message);
//                            console.log(data.message);
////                    }
//                        });
                    // End socket.io listener
                    app.loadMessages();
                }
            },
            loadUser() {
                let app = this;
                axios.get('ajax/users').then(res => {
                    app.users = res.data;
//                    console.log(app.users);
                })
            },
            sendMessage() {
                let app = this;
                axios.post('ajax/messages/send', {
                    'body': app.body,
                    'sender_id': window.Laravel.user.id,
                    'receiver_id': app.receiverUserId,
                }).then(res => {
                    app.messages.push(res.data);
                    app.body = '';
                });
            },
            loadMessages() {
                console.log('load messages');
                let app = this;
                app.loadingMessages = true;
                app.messages = [];
                axios.post('ajax/messages', {
                    sender_id: app.receiverUserId
                }).then((res) => {
                    app.messages = res.data;
                    app.loadingMessages = false;
                })
            },
            usersJoin(users) {
                let app = this;
                console.log('usersJoin');
                this.users = users;
                this.online = this.users.length;
                this.users.map(function (user) {
                    app.setUser(user);
                });
                console.log(users);
            },
            userJoin(user) {
                console.log('userJoin');
                this.users.push(user);
                this.online++;
                this.setUser(user);
                console.log(this.users);
                console.log(this.online);
            },
            userLeft(user) {
                console.log('userLeft');
                this.users = this.users.filter(function (u) {
                    return user.id !== u.id
                });
                this.online--;
                console.log(this.users);
                console.log(this.online);
            },
            listenMessage(data) {
                let app = this;
                this.messages.push(data.message);
                if (app.receiverUserId !== data.message.sender_id) {
                    this.users.some(function (user) {
                        if (user.id === data.message.sender_id) {
                            app.unreadMessage++;
                            user.message = data.message.body;
                            user.unread += 1;
                        }
                    });
                }
            },
            digit(number) {
                return persianDigit(number);
            },
            setUser(user) {
                user.unread = 0;
                user.message = '';
            },
            scroll() {
                let element = this.$refs.messagebox;
                element.scrollTop = element.scrollHeight;
            }
        }
    }
</script>

