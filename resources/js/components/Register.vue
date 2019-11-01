<template>

    <div class="wrap-register">

        <el-form>
            <div style="text-align: center; font-size: 20px;">
                Register
            </div>

            <el-alert v-if="regError"
                :title='regError'
                type="error" 
                effect="dark"></el-alert>

            <el-form-item style="margin-top: 20px;">
                <el-input v-model="$v.formRegister.name.$model" placeholder="Your Name" type="text"></el-input>
                <p v-if="errors" class="error">
                    <span v-if="!$v.formRegister.name.required">Your name is required.</span>
                </p>
            </el-form-item>

            <el-form-item>
                <el-input v-model="$v.formRegister.email.$model" placeholder="Your Email Address" type="email"></el-input>
                <p v-if="errors" class="error">
                    <span v-if="!$v.formRegister.email.required">Your email address is required.</span>
                    <span v-if="!$v.formRegister.email.email">Your email address is invalid.</span>
                </p>
            </el-form-item>

            <el-form-item>
                <el-input v-model="$v.formRegister.password.$model" placeholder="Your Chosen Password" type="password"></el-input>
                <p v-if="errors" class="error">
                    <span v-if="!$v.formRegister.password.required">You need a password.</span>
                    <span v-if="!$v.formRegister.password.minLength">You need at least 8 characters.</span>
                </p>
            </el-form-item>

            <el-form-item class="text-right">
                <el-button type="primary" @click.prevent="register">Register</el-button>
            </el-form-item>
        </el-form>
    
        
    </div>
</template>

<script>
    import {registerUser} from '../helpers/auth'; // auth helpers
    import { required, minLength, email } from 'vuelidate/lib/validators'; // for validation

    export default {
        data(){
            return {
                uiState : "submit not clicked",
                errors : false,
                empty : true,
                formRegister : {
                    name        : null,
                    email       : null,
                    password    : null
                },
            }
        },
        
        methods:{
            register(){
                this.empty = !this.$v.formRegister.$anyDirty;
                this.errors = this.$v.formRegister.$anyError;
                this.uiState = "submit clicked";

                if(this.errors === false && this.empty === false) {
                    console.log('no error');

                    registerUser(this.$data.formRegister)
                    .then(res => {
                        this.$store.commit("registerSuccess", res);
                        this.$router.push({path: '/login'});
                    })
                    .catch(error => {
                        this.$store.commit("registerFailed", {error});
                    });

                    this.uiState = "form submitted";
                }
                else {
                    console.log(this.empty + ', ' + this.errors);
                }
            }
        },

        validations : {
            formRegister : {
                name : {
                    required
                },
                
                email : {
                    required,
                    email
                },

                password : {
                    required,
                    minLength : minLength(8)
                }
            }
        },
        
        computed:{
            regError(){
                return this.$store.getters.regError
            }
        }
    }
</script>

<style scoped>
    .error {
        text-align: center;
        color: red;
    }

    .wrap-register {
        width: 300px;
        position: absolute;
        left: calc((100vw / 2 ) - 150px);
        top: 100px;
    }
</style>