let user = window.App.user;
module.exports = {
    UpdateReply(reply) {
        return reply.user_id === user.id;
    }
};
