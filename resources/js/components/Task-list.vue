<template>

    <el-row>
        <el-col :span="4">
            <el-menu default-active="1" class="el-menu-vertical">
                <el-menu-item index="1">
                    <i class="fa fa-fw fa-list"></i> &nbsp;
                    Current Tasks
                </el-menu-item>

                <el-menu-item index="2">
                    <i class="fa fa-fw fa-archive"></i> &nbsp;
                    Completed Tasks
                </el-menu-item>
            </el-menu>
        </el-col>
        <el-col :span="16">
            <div id="wrap">
                <el-card class="box-card">
                    <div slot="header">
                        <h3>Current Tasks</h3>
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
                    <div class="task-item" v-for="task in list" :key="task.id">
                        <i class="fa fa-fw fa-square-o"></i>
                        <span  v-bind:title="message">
                            {{ task.body }}
                        </span>
                        <div 
                            class="deadline-details"
                            v-if="task.deadline !== null"
                            track-by="id"
                            >
                            <i class="fa fa-fw fa-calendar"></i>
                            <span class="date">
                                {{ filterDate(task.deadline) }}
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
    <!-- <el-container>
    </el-container> -->
    <!-- columns -->
    <!-- <div class="columns">
        <div class="column is-half is-offset-one-quarter task-list">
            <div class="box">
                <h2 class="title">My Tasks</h2>
                <hr> -->

                <!-- input fields -->
                <!-- <div class="field has-addons">
                    <div class="control is-expanded">
                        <input type="text" class="input" placeholder="New Task" v-model="task.body">
                    </div>
                    <div class="control">
                        <a href="#" class="button is-primary" @click="createTask()">
                            Add Task
                        </a>
                    </div>
                </div> e.o input fields -->

                <!-- tabs - current / archived -->
                <!-- <div class="tabs is-centered">
                    <ul>
                        <li :class="{'is-active':isActive('current')}">
                            <h3 class="title">
                                <a href="#" v-on:click.prevent="fetchTaskList()">
                                    Current Task
                                </a>
                            </h3>
                        </li>
                        <li :class="{'is-active':isActive('archive')}">
                            <h3 class="title">
                                <a href="#" v-on:click.prevent="fetchTaskList()">
                                    Archived Tasks
                                </a>
                            </h3>
                        </li>
                    </ul>
                </div> e.o tabs -->

                <!-- for each task -->
                <!-- <div class="card" v-for="tasks in list">
                    <header class="card-header">
                        <p class="card-header title">
                            Task {{ task.id }}
                        </p>
                        <a href="#" class='card-header-icon' aria-label="more options" v-on:click.prevent="archiveTask(task.id)">
                            <span class="icon">
                                <i class="fa" :class="{ 'fa-square-o' : !task.archive, check : !task.archive , 'fa-check-square-o' : task.archive, done : task.archive }" aria-hidden="true"></i>
                            </span>
                        </a>
                    </header>
                    <div class="card-content">
                        <div class="content">
                            <p  v-if="task !== editingTask" @dblclick="editTask(task)" v-bind:title="message">
                                {{ task.body }}
                            </p>
                        </div>
                        <input type="text" class="input" v-if="task === editingTask" v-autofocus @keyup.enter="endEditing(task)" @blur="endEditing(task)" placeholder="New Task" v-model="task.body">
                    </div>
                    <footer class="card-footer">
                        <a class="card-footer-item" v-on:click.prevent="deleteTask(task.id)"></a>
                    </footer>
                </div> -->
                <!-- e.o for each task -->
            <!-- </div> e.o box -->

        <!-- </div>  -->
    <!-- </div> e.o columns -->
</template>

<script>

// import 'element-ui/lib/theme-chalk/reset.css';
import moment from 'moment'

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
            task : {
                id : '',
                body : '',
                archive : '',
                deadline : '',
            },
            editingTask : {},
            activeItem : 'current',
            input : ''
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
                this.list = result.data
            });

            this.checkDeadline();
        },

        checkDeadline() {
            setInterval(function() {
                var date = new Date();
                var time = date.getTime();
                console.log(time);
            }, 10 * 1000);
        },

        filterDate(dt) {
            if(dt) {
                return moment(String(dt)).format('DD MMM YY')
            }
        },

        filterTime(dt) {
            if(dt) {
                return moment(String(dt)).format('hh:mm A')
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

        .deadline-details {
            position: absolute;
            right: 0;
            top: 0;
            color: #999;
            font-weight: bolder;
            font-style: italic;
        }
    }
</style>