<template>
    <el-menu mode="horizontal">
        <el-menu-item index="1"><router-link to="/dashboard">Viktodo</router-link></el-menu-item>

        <template v-if="!currentUser">
            <el-menu-item index="2" style="float: right;">
                <router-link to="/login">Login</router-link>
            </el-menu-item>
            <el-menu-item index="2" style="float: right;">
                <router-link to="/register">Register</router-link>
            </el-menu-item>
            <div class="clearfix"></div>
        </template>

        <template v-else="">
            <!-- <li>
                <router-link to="/dashboard" class="nav-link">Dashboard</router-link>
            </li> -->
            
            
            <el-menu-item index="3" style="float:right;">
                <span @click.prevent="logout">Logout</span>
            </el-menu-item>

            <span style="display: inline-block; height: 60px; line-height: 60px; padding: 0 10px; float: right;">
                Welcome <b>{{ currentUser.name }}!</b>
            </span>

            <div class="clearfix"></div>
            
        </template>
    </el-menu>

</template>

<script>
    export default {
        name : 'app-header',
        methods : {
            logout() {
                this.$store.commit('logout');
                this.$router.push('/login');
            }
        },
        computed : {
            currentUser() {
                return this.$store.getters.currentUser;
            }
        }
    }
</script>