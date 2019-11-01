<template>
<div class="login">
<el-alert v-if="registeredUser" 
        :title="'Thank You ' + registeredUser.name + '. You can now login to Viktodo.'" 
        type="success" 
        effect="dark"></el-alert>
    
    <el-form>
        <div style="text-align: center; font-size: 20px;">
            Login
        </div>

        <el-alert v-if="authError"
                :title='authError'
                type="error" 
                effect="dark"></el-alert>

        <el-form-item label="Email">
            <el-input v-model="formLogin.email" placeholder="Email Address" type="email"></el-input>
        </el-form-item>

        <el-form-item label="Password">
            <el-input v-model="formLogin.password" placeholder="Password" type="password"></el-input>
        </el-form-item>

        <el-form-item class="text-right">
            <el-button type="primary" @click.prevent="authenticate">Login</el-button>
        </el-form-item>
    </el-form>
</div>
</template>

<script>
    import {login} from '../helpers/auth';
    export default {
        data() {
            return {
                formLogin : {
                    email : '',
                    password : ''
                },
                error : null
            }
        },
        methods:{
            authenticate(){
                this.$store.dispatch('login');
                login(this.$data.formLogin)
                    .then(res => {
                        this.$store.commit("loginSuccess", res);
                        this.$router.push({path: '/dashboard'});
                    })
                    .catch(error => {
                        this.$store.commit("loginFailed", {error});
                    });
            }
        },

        computed : {
            authError() {
                return this.$store.getters.authError
            },

            registeredUser() {
                return this.$store.getters.registeredUser
            }
        }
    }
</script>

<style scoped>
    .error {
        text-align: center;
        color: red;
    }

    .login {
        width: 300px;
        position: absolute;
        left: calc((100vw / 2 ) - 150px);
        top: 100px;
    }
</style>