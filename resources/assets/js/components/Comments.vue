<template>
    <div class="comments-app">
        <span class="comment-count">{{ this.count }} Comment{{ this.count > 1 ? 's' : '' }}</span>
        <!-- Form -->
        <div class="comment-form" v-if="!has_open">
            <!-- Comment Avatar -->
            <div class="comment-avatar">
                <img src="images/user.png">
            </div>

            <form class="form" name="form" @submit.prevent="saveComment">
                <div class="form-row">
                    <textarea ref="txt_comment" class="input" placeholder="Add a comment..." required v-model="message"></textarea>
                </div>
                <div class="form-row">
                    <span v-if="error_comment" style="color: red;">{{ error_comment }}</span>
                </div>
                <div class="form-row">
                    <input type="button" class="btn btn-primary" @click="saveComment" value="Post as">
                    <input class="input name" placeholder="Your name" type="text" v-model="user_name">
                </div>
                <div class="form-row">
                    <span v-if="error_commenter" style="color: red;">{{ error_commenter }}</span>
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
                    <div class="comment-info">
                        <span class="comment-author">
                            {{ comment.user_name }}
                        </span>
                        <span class="comment-date">{{ comment.date}}</span>
                    </div>
                    <div class="comment-text">{{ comment.comment }}</div>
                    <div class="comment-footer">
                        <div class="comment-actions">
                            <ul class="list">
                                <li>
                                    <a v-on:click="openComment(index)">{{ comment_boxes[index] ? 'Cancel' : 'Reply' }}</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- From -->
                <div class="comment-form comment-v" v-if="comment_boxes[index]">
                    <!-- Comment Avatar -->
                    <div class="comment-avatar">
                        <img src="images/user.png">
                    </div>
                    <form class="form" name="form" v-on:submit.prevent="saveReply(comment.id, index, 0, 1)">
                        <div class="form-row">
                            <textarea :ref="'txt_comment' + index" class="input" placeholder="Add a comment..." required v-model="message"></textarea>
                        </div>
                        <div class="form-row">
                            <span v-if="error_reply" style="color: red;">{{ error_reply }}</span>
                        </div>
                        <div class="form-row">
                            <input type="button" class="btn btn-primary" v-on:click="saveReply(comment.id, index, 0, 1)" value="Post as">
                            <input class="input" placeholder="Your name" type="text" v-model="user_name">
                        </div>
                        <div class="form-row">
                            <span v-if="error_replier" style="color: red;">{{ error_replier }}</span>
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
                            <div class="comment-box">
                                <div class="comment-info">
                                    <span class="comment-author">
                                        {{ reply.user_name }}
                                    </span>
                                    <span class="comment-date">{{ reply.date }}</span>
                                </div>
                                <div class="comment-text">{{ reply.comment }}</div>
                                <div class="comment-footer">
                                    <div class="comment-actions">
                                        <ul class="list">
                                            <li>
                                                <a v-on:click="openReply(index2)">{{ reply_comment_boxes[index2] ? 'Cancel' : 'Reply' }}</a>
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
                                        <textarea :ref="'txt_reply' + index2" class="input" placeholder="Add a comment..." required v-model="message"></textarea>
                                    </div>
                                    <div class="form-row">
                                        <span v-if="error_reply" style="color: red;">{{ error_reply }}</span>
                                    </div>
                                    <div class="form-row">
                                        <input type="button" class="btn btn-primary" v-on:click="saveReply(reply.id, index, index2, 2)" value="Post as">
                                        <input class="input" placeholder="Your name" type="text" v-model="user_name">
                                    </div>
                                    <div class="form-row">
                                        <span v-if="error_replier" style="color: red;">{{ error_replier }}</span>
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
                                        <div class="comment-box">
                                            <div class="comment-info">
                                                <span class="comment-author">
                                                {{ reply2.user_name }}
                                                </span>
                                                <span class="comment-date">{{ reply2.date }}</span>
                                            </div>
                                            <div class="comment-text">{{ reply2.comment }}</div>
                                            <div class="comment-footer">
                                                <div class="comment-actions">
                                                    <br/>
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
                count: 0,
                comment_boxes: [],
                reply_comment_boxes: [],
                message: null,
                user_name: null,
                has_open: false,
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
                    this.sortComments(); // sort the comments/replies by latest upon load
                    this.countComments(); // count the comments/replies upon load
                });
            },
            sortComments() {
                // sort comments/replies by latest
                this.comments = _.orderBy(this.comments, ['created_at'], ['desc']);

                this.comments.forEach(function(comment) {
                    comment.replies = _.orderBy(comment.replies, ['created_at'], ['desc']);

                    comment.replies.forEach(function(reply) {
                        reply.replies = _.orderBy(reply.replies, ['created_at'], ['desc']);
                    });
                });
            },
            countComments() {
                // count comment.replies and show it
                this.count = this.comments.length;

                let replies = 0;
                this.comments.forEach(function (comment) {
                    replies += comment.replies.length;

                   comment.replies.forEach(function (reply) {
                        replies += reply.replies.length;
                    }); 
                });

                this.count += replies;
            },
            openComment(index) {
                // clear inputs
                this.message = null;
                this.user_name = null;
                this.error_comment = null;
                this.error_commenter = null;

                if (this.comment_boxes[index]) {
                    // close comment form
                    Vue.set(this.comment_boxes, index, 0);

                    // auto-focus to main comment
                    this.$nextTick(() => {
                        this.$refs.txt_comment.focus();
                    });

                    this.has_open = false;
                } else {
                    // close other comment forms
                    for (let i = 0; i < this.comment_boxes.length; i++) {
                        if (this.comment_boxes[i])     
                            Vue.set(this.comment_boxes, i, 0);
                    }
                    for (let i = 0; i < this.reply_comment_boxes.length; i++) {
                        if (this.reply_comment_boxes[i])     
                            Vue.set(this.reply_comment_boxes, i, 0);
                    }

                    // open comment form
                    Vue.set(this.comment_boxes, index, 1);

                    // auto-focus to input
                    this.$nextTick(() => {
                        this.$refs['txt_comment' + index][0].focus();
                    });

                    this.has_open = true;
                }
            },
            openReply(index) {
                // clear inputs
                this.message = null;
                this.user_name = null;
                this.error_reply = null;
                this.error_replier = null;

                if (this.reply_comment_boxes[index]) {
                    // close comment form
                    Vue.set(this.reply_comment_boxes, index, 0);

                    // auto-focus to main comment
                    this.$nextTick(() => {
                        this.$refs.txt_comment.focus();
                    });

                    this.has_open = false;
                } else {
                    // close other comment forms
                    for (let i = 0; i < this.comment_boxes.length; i++) {
                        if (this.comment_boxes[i])     
                            Vue.set(this.comment_boxes, i, 0);
                    }
                    for (let i = 0; i < this.reply_comment_boxes.length; i++) {
                        if (this.reply_comment_boxes[i])     
                            Vue.set(this.reply_comment_boxes, i, 0);
                    }

                    // open comment form
                    Vue.set(this.reply_comment_boxes, index, 1);

                    // auto-focus to input
                    this.$nextTick(() => {
                        this.$refs['txt_reply' + index][0].focus();
                    });

                    this.has_open = true;
                }
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
                                "reply": 0, // level 1 comments
                                "replies": [],
                                "created_at": res.data.comment.created_at,
                                "date": res.data.comment.date
                            });
                            // auto-focus to main comment
                            this.$nextTick(() => {
                                this.$refs.txt_comment.focus();
                            });
                            
                            this.sortComments(); // sort the comments/replies by latest upon save
                            this.countComments(); // count the comments/replies upon save

                            // clear inputs
                            this.message = null;
                            this.user_name = null;
                            this.has_open = false;
                        }
                    });
                }
                else {
                    // validation messages
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
                        reply: level, // levels 2-3 comments
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

                            // auto-focus to main comment
                            this.$nextTick(() => {
                                this.$refs.txt_comment.focus();
                            });
                            
                            this.sortComments(); // sort the comments/replies by latest upon save
                            this.countComments(); // count the comments/replies upon save

                            // clear inputs
                            this.message = null;
                            this.user_name = null;
                            this.has_open = false;
                        }
                    });
                }
                else {
                    //validation messages
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

            this.$refs.txt_comment.focus();
        }
    }
</script>