<template>
    <el-row>
        <el-dialog 
            :title="'Hay ' + currentUser.name + '! You missed out some tasks while you were away.'" 
            :visible.sync="dialogInformMissedTask">
            <el-table :data="missedTasks" height="300" class="due">
                <el-table-column property="body" label="Tasks">
                    <template slot-scope="scope">
                        <i class="fa fa-fw fa-caret-right"></i>
                        {{ scope.row.body }}
                    </template>
                </el-table-column>
                <el-table-column property="deadline" label="Deadline"></el-table-column>
            </el-table>

            <div style="text-align: right; margin-top: 20px;">
                <el-button @click="dialogInformMissedTask = false" type="primary">Got it!</el-button>
            </div>
        </el-dialog>

        <el-col :span="4">
            &nbsp;
        </el-col>
        
        <el-col :span="16">
            <div id="wrap">
                <el-card class="box-card">
                    <div slot="header">
                        <h3>Your Tasks</h3>
                    </div>

                    <div class="task-item new">
                        <el-container>
                            <el-row :gutter="20">
                                <el-col :span="20">
                                    <el-input
                                        placeholder="Add new tasks."
                                        v-model="task.body"
                                        class="input-add-task"
                                        type="text">
                                    </el-input>
                                </el-col>
                                <el-col :span="4">
                                    <el-date-picker
                                        v-model="task.deadline"
                                        type="datetime"
                                        placeholder="Select date and time">
                                    </el-date-picker>
                                </el-col>
                            </el-row>
                        </el-container>

                        <el-button
                            type="success"
                            class="btn-add"
                            @click="createTask()">
                            <i class="fa fa-fw fa-plus"></i>
                        </el-button>
                    </div>

                    <!-- TODO : list all current tasks -->
                    <div class="task-item" v-for="(task) in list" :key="task.id" 
                        v-on:click="archiveTask(task.id)"
                        :class=" { completed : task.archive } ">
                        <i :class="{ 'fa fa-fw fa-check-square' : task.archive, 'fa fa-fw fa-square-o' : !task.archive }"></i>
                        <span  v-bind:title="message">
                            {{ task.body }}
                        </span>
                        <div class="deadline-details"
                            v-if="task.deadline !== null"
                            track-by="id"
                            >
                            <i class="fa fa-fw fa-calendar"></i>
                            <span class="date">
                                {{ filterDate(task.deadline) }}
                                <!-- {{ task.deadline }} -->
                            </span>
                            <span class="time">
                                {{ filterTime(task.deadline) }}
                            </span>
                        </div>
                    </div>
                </el-card>
            </div>
        </el-col>
    </el-row>

</template>

<script>
    import moment from 'moment';
    import 'moment-timezone';
    import {Notification} from 'element-ui';

    export default {
        directives : {
            'autofocus' : {
                inserted(el) {
                    el.focus();
                }
            }
        },

        data() {
            return {
                message : "Double click to edit.",
                list : [],
                upcomingTasks : [],
                missedTasks : [],
                task : {
                    id : '',
                    body : '',
                    archive : '',
                    deadline : '',
                },
                editingTask : {},
                activeItem : 'current',
                input : '',
                isCompleted : false,
                dialogInformMissedTask : false
            }
        },

        created() {
            this.fetchTaskList();
        },

        methods : {
            fetchTaskList(archive) {
                if (archive == "c") {
                    var url = 'current_tasks';
                    this.setActive('current');
                }
                else if(archive == "a") {
                    var url = 'archived_tasks';
                    this.setActive('archive');
                } else {
                    var url = 'all_tasks';
                    this.setActive('all');
                }

                axios.get(url).then(result => {
                    this.list = result.data;
                    this.getUpcomingTasks(this.list);
                });
                
            },

            getUpcomingTasks(tasks) {
                for(var t in tasks) {
                    var task = tasks[t],
                        pastDate = moment(task.deadline).isBefore(moment());

                    if(task.deadline !== null && !pastDate) {
                        this.upcomingTasks.push(task);
                    }

                    if(task.deadline !== null && pastDate) {
                        this.missedTasks.push(task);
                    }
                }

                this.notify(this.upcomingTasks);
                this.informMissedTasks(this.missedTasks);
            },

            notify(uts) {
                setInterval(function() {

                    for(var upTask in uts) {
                        
                        var ut = uts[upTask];

                        var isDue = moment(ut.deadline).isBefore(moment());
                        if(isDue) {

                            Notification.info({
                                title : 'Task Reminder',
                                message: 'You have a due task! ' + ut.body + 'is due ' + moment(ut.deadline).tz('Asia/Yangon').format('DD MMM YY hh:mm A') + '.',
                                duration: 30
                            });
                        }
                    }

                }, 60 * 1000); // every 1 minute
            },

            informMissedTasks(mTasks) {
                if(mTasks.length != 0) {
                    this.dialogInformMissedTask = true;
                }
                else {
                    this.dialogInformMissedTask = false;
                }
            },

            filterDate(dt) {
                if(dt) {
                    return moment(String(dt)).tz('Asia/Yangon').format('DD MMM YY')
                }
            },

            filterTime(dt) {
                if(dt) {
                    return moment(String(dt)).tz('Asia/Yangon').format('hh:mm A')
                }
            },

            isActive(menuItem) {
                return this.menuItem = menuItem;
            },

            setActive(menuItem) {
                return this.activeItem = menuItem;
            },

            isFocus() {
                console.log(this.task);
            },

            createTask() {
                console.log(this.task.deadline);
                axios.defaults.headers.post["Content-Type"] = "application/json";
                axios.post('create_task', this.task).then(result => {
                    this.task.body = '',
                    this.fetchTaskList(false);
                }).catch(err => {
                    console.log(err);
                    // todo : alert user the error
                });
            },

            editTask(task) {
                this.editingTask = task;
            },

            endEditing(task) {
                this.editingTask = {};
                if(task.body.trim() === '') {
                    this.deleteTask(task.id);
                } else {
                    axios.post('edit_task', this.task).then(result => {
                        console.log('success');
                    }).catch(err => {
                        console.log(err);
                        // todo : alert user the error
                    });
                }
            },

            deleteTask(id) {
                axios.post('delete_task/' + id).then(result => {
                    this.fetchTaskList();
                }).catch(err => {
                    console.log(err);
                    // todo : alert user the error
                });
            },
            
            archiveTask(id) {
                axios.post('archive_task/' + id).then(result => {
                    this.fetchTaskList();
                }).catch(err => {
                    console.log(err);
                    // todo : alert user the error
                });
            }
        },

        computed : {
            currentUser() {
                return this.$store.getters.currentUser
            },
        }
    }
</script>

<style lang="scss">

    .el-menu-vertical {
        height: 100vh;
    }

    #wrap {
        padding: 10px;
    }

    .task-item {
        min-height: 40px;
        line-height: 40px;
        border-bottom: 1px solid #eee;
        position: relative;
        cursor: pointer;
        user-select: none;

        &.new {
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
            margin-bottom: 5px;

            .btn-add {
                position: absolute;
                right: 0;
                top: 0;
            }
        }

        &.completed {
            text-decoration: line-through;
            color: #ccc;
            font-style: italic;

            .fa {
                color: #67C23A;
            }
        }

        .deadline-details {
            position: absolute;
            right: 0;
            top: 0;
            color: #999;
            font-weight: bolder;
            font-style: italic;
        }
    }

    .due {
        font-weight: bold;
        font-style: italic;
    }
</style>