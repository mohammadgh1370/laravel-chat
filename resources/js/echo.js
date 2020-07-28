import { EventBus } from './event';

window.Echo.join('chat')
    .here(users => {
        users = users.filter(function (user) {
            return window.Laravel.user.id !== user.id
        });
        EventBus.$emit('users_join',users);
    })
    .joining(user => {
        EventBus.$emit('user_join',user);
    })
    .leaving(user => {
        EventBus.$emit('user_left',user);
    });

window.Echo.channel('chat-'+window.Laravel.user.id).listen('.message_send',function (data) {
    EventBus.$emit('chat-'+window.Laravel.user.id,data);
});
