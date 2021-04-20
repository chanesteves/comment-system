<template>
    <div class="comments-app">
        <h1>Comments</h1>
        <!-- Form -->
        <div class="comment-form">
            <!-- Comment Avatar -->
            <div class="comment-avatar">
                <img src="images/user.png">
            </div>

            <form class="form" name="form" @submit.prevent="saveComment">
                <div class="form-row">
                    <textarea class="input" placeholder="Add comment..." required v-model="message"></textarea>
                    <span class="input" v-if="error_comment" style="color: red;">{{ error_comment }}</span>
                </div>
                <div class="form-row">
                    <input class="input" placeholder="Your name" type="text" v-model="user_name">
                    <span class="input" v-if="error_commenter" style="color: red;">{{ error_commenter }}</span>
                </div>
                <div class="form-row">
                    <input type="button" class="btn btn-success" @click="saveComment" value="Add Comment">
                </div>
            </form>
        </div>
        <!-- Comments List -->
        <div class="comments" v-if="comments" v-for="(comment, index) in comments">
            <!-- Comment -->
            <div class="comment">
                <!-- Comment Avatar -->
                <div class="comment-avatar">
                    <img src="images/user.png">
                </div>
                <!-- Comment Box -->
                <div class="comment-box">
                    <div class="comment-text">{{ comment.comment }}</div>
                    <div class="comment-footer">
                        <div class="comment-info">
                            <span class="comment-author">
                               <em>{{ comment.user_name }}</em>
                            </span>
                            <span class="comment-date">{{ comment.date}}</span>
                        </div>
                        <div class="comment-actions">
                            <ul class="list">
                                <li>
                                    <a v-on:click="openComment(index)">Reply</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>=
                <!-- From -->
                <div class="comment-form comment-v" v-if="comment_boxes[index]">
                    <!-- Comment Avatar -->
                    <div class="comment-avatar">
                        <img src="images/user.png">
                    </div>
                    <form class="form" name="form" v-on:submit.prevent="saveReply(comment.id, index, 0, 1)">
                        <div class="form-row">
                            <textarea class="input" placeholder="Add comment..." required v-model="message"></textarea>
                            <span class="input" v-if="error_reply" style="color: red;">{{ error_reply }}</span>
                        </div>
                        <div class="form-row">
                            <input class="input" placeholder="Your name" type="text" v-model="user_name">
                            <span class="input" v-if="error_replier" style="color: red;">{{ error_replier }}</span>
                        </div>
                        <div class="form-row">
                            <input type="button" class="btn btn-success" v-on:click="saveReply(comment.id, index, 0, 1)" value="Add Comment">
                        </div>
                    </form>
                </div>
                <!-- Comment - Reply -->
                <div v-if="comment.replies">
                    <div class="comments" v-for="(reply, index2) in comment.replies">
                        <div class="comment reply">
                            <!-- Comment Avatar -->
                            <div class="comment-avatar">
                                <img src="images/user.png">
                            </div>
                            <!-- Comment Box -->
                            <div class="comment-box" style="background: grey;">
                                <div class="comment-text" style="color: white">{{ reply.comment }}</div>
                                <div class="comment-footer">
                                    <div class="comment-info">
                                        <span class="comment-author">
                                           {{ reply.user_name }}
                                        </span>
                                        <span class="comment-date">{{ reply.date }}</span>
                                    </div>
                                    <div class="comment-actions">
                                        <ul class="list">
                                            <li>
                                                <a v-on:click="openReply(index2)">Reply</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <!-- From -->
                            <div class="comment-form reply" v-if="reply_comment_boxes[index2]">
                                <!-- Comment Avatar -->
                                <div class="comment-avatar">
                                    <img src="images/user.png">
                                </div>
                                <form class="form" name="form" v-on:submit.prevent="saveReply(reply.id, index, index2, 2)">
                                    <div class="form-row">
                                        <textarea class="input" placeholder="Add comment..." required v-model="message"></textarea>
                                    </div>
                                    <div class="form-row">
                                        <input class="input" placeholder="Your name" type="text" v-model="user_name">
                                        <span class="input" v-if="error_reply" style="color: red;">{{ error_reply }}</span>
                                        <span class="input" v-if="error_replier" style="color: red;">{{ error_replier }}</span>
                                    </div>
                                    <div class="form-row">
                                        <input type="button" class="btn btn-success" v-on:click="saveReply(reply.id, index, index2, 2)" value="Add Comment">
                                    </div>
                                </form>
                            </div>

                            <!-- Comment - Reply - Reply -->
                            <div v-if="reply.replies">
                                <div class="comments" v-for="(reply2, index3) in reply.replies">
                                    <div class="comment reply">
                                        <!-- Comment Avatar -->
                                        <div class="comment-avatar">
                                            <img src="images/user.png">
                                        </div>
                                        <!-- Comment Box -->
                                        <div class="comment-box" style="background: grey;">
                                            <div class="comment-text" style="color: white">{{ reply2.comment }}</div>
                                            <div class="comment-footer">
                                                <div class="comment-info">
                                                    <span class="comment-author">
                                                    {{ reply2.user_name }}
                                                    </span>
                                                    <span class="comment-date">{{ reply2.date }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    var _ = require('lodash');
    export default {
        props: ['commentUrl'],
        data() {
            return {
                comments: [],
                replies1: [],
                replies2: [],
                count: 0,
                comment_boxes: [],
                reply_comment_boxes: [],
                message: null,
                user_name: null,
                view_comment: [],
                show: [],
                error_comment: null,
                error_commenter: null,
                error_reply: null,
                error_replier: null
            }
        },

        http: {
            headers: {
                'X-CSRF-TOKEN': window.csrf
            }
        },

        methods: {
            fetchComments() {
                this.$http.get('comments/' + this.commentUrl).then(res => {
                    this.comments = res.data;
                    this.sortComments();
                });
            },
            showComments(index) {
                if (!this.view_comment[index]) {
                    Vue.set(this.show, index, "hide");
                    Vue.set(this.view_comment, index, 1);
                } else {
                    Vue.set(this.show, index, "view");
                    Vue.set(this.view_comment, index, 0);
                }
            },
            sortComments() {
                this.comments = _.orderBy(this.comments, ['created_at'], ['desc']);

                this.comments.forEach(function(comment) {
                    comment.replies = _.orderBy(comment.replies, ['created_at'], ['desc']);

                    comment.replies.forEach(function(reply) {
                        reply.replies = _.orderBy(reply.replies, ['created_at'], ['desc']);
                    });
                });
            },
            openComment(index) {
                if (this.comment_boxes[index]) {
                    Vue.set(this.comment_boxes, index, 0);
                } else {
                    Vue.set(this.comment_boxes, index, 1);
                }
            },
            openReply(index) {
                if (this.reply_comment_boxes[index])
                    Vue.set(this.reply_comment_boxes, index, 0);
                else
                    Vue.set(this.reply_comment_boxes, index, 1);
            },
            saveComment() {
                this.error_comment = null;
                this.error_commenter = null;

                if (this.message && this.message.trim() != '' && this.user_name && this.user_name.trim() != '') {
                    this.$http.post('comments', {
                        page_id: this.commentUrl,
                        comment: this.message,
                        reply: 0,
                        user_name: this.user_name
                    }).then(res => {
                        if (res.data.status) {
                            this.comments.push({
                                "id": res.data.comment.id,
                                "user_name": this.user_name,
                                "comment": this.message,
                                "reply": 0,
                                "replies": [],
                                "created_at": res.data.comment.created_at,
                                "date": res.data.comment.date
                            });
                            this.sortComments();
                            this.message = null;
                            this.user_name = null;
                        }
                    });
                }
                else {
                    if (!this.message || this.message.trim() == '')
                        this.error_comment = "Please enter a comment";
                    if (!this.user_name || this.user_name.trim() == '')
                        this.error_commenter = "Please enter your name";
                }
            },
            saveReply(id, index, index2, level) {
                this.error_reply = null;
                this.error_replier = null;
                if (this.message != null && this.message.trim() != '' && this.user_name && this.user_name.trim() != '') {
                    this.$http.post('comments', {
                        comment: this.message,
                        reply: level,
                        user_name: this.user_name,
                        reply_id: id
                    }).then(res => {
                        if (res.data.status) {
                            if (level == 1 && typeof this.comments[index] !== 'undefined') {
                                this.comments[index].replies.push({
                                    "id": res.data.comment.id,
                                    "user_name": this.user_name,
                                    "comment": this.message,
                                    "created_at": res.data.comment.created_at,
                                    "date": res.data.comment.date
                                });
                                Vue.set(this.reply_comment_boxes, index, 0);
                            }
                            else if (level == 2 && typeof this.comments[index] !== 'undefined' && typeof this.comments[index].replies[index2] !== 'undefined') {
                                this.comments[index].replies[index2].replies.push({
                                    "id": res.data.comment.id,
                                    "user_name": this.user_name,
                                    "comment": this.message,
                                    "created_at": res.data.comment.created_at,
                                    "date": res.data.comment.date
                                });
                                Vue.set(this.reply_comment_boxes, index2, 0);
                            }
                            Vue.set(this.comment_boxes, index, 0);
                            this.message = null;
                            this.user_name = null;
                            this.sortComments();
                        }
                    });
                }
                else {
                    if (!this.message || this.message.trim() == '')
                        this.error_reply = "Please enter a reply";
                    if (!this.user_name || this.user_name.trim() == '')
                        this.error_replier = "Please enter your name";
                }
            }
        },
        mounted() {
            console.log("mounted");
            this.fetchComments();
        }
    }
</script>