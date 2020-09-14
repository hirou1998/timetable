<template>
    <div>
        <main>
            <div class="container form-container">

              <!-- <div class="form-group has-error" v-if="errors.length !== 0">
                  <div class="alert alert-danger text-center">
                      Errors happened while registrating a user
                      <div v-for="(error, key, index) in errors" :key="index">{{key}}: {{error}}</div>
                  </div>
              </div> -->

              <ValidationObserver
                class="form-block"
                ref="observer"
                action="/register"
                id="register"
                method="post"
                tag="form"
                @submit.prevent="register()"
                v-slot="{ invalid }"
              >
                <input type="hidden" name="_token" :value="csrf">

                <div class="top-image">
                    <router-link to="/">
                    <img src="/images/top.png" alt="">
                    </router-link>
                </div>
                <h1 class="top-title">時間割</h1>
                <p class="register-text">時間割アプリへようこそ</p>

                <div class="form-group">
                    <div class="col-md-6">
                        <validation-provider name="名前" rules="required|max:20" v-slot="{errors}">
                            <input 
                              v-model="name" 
                              name="name" 
                              type="text" class="form-control top-button"
                              placeholder="ユーザー名"
                            />
                            <div class="alert alert-danger" v-show="errors[0]">{{errors[0]}}</div>
                        </validation-provider>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-6">
                        <validation-provider
                            name="メールアドレス"
                            rules="required|email"
                            v-slot="{errors}"
                        >
                            <input 
                              v-model="email" name="email" 
                              type="email" class="form-control top-button"
                              placeholder="メールアドレス"
                            />
                            <div class="alert alert-danger" v-show="errors[0]">{{errors[0]}}</div>
                        </validation-provider>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-6">
                        <validation-provider
                            name="パスワード"
                            rules="required|min:8|confirmed:password_confirmation"
                            v-slot="{errors}"
                        >
                            <input
                                v-model="password"
                                name="password"
                                type="password"
                                class="form-control top-button"
                                placeholder="パスワード"
                            />
                            <div class="alert alert-danger" v-show="errors[0]">{{errors[0]}}</div>
                        </validation-provider>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-6">
                        <validation-provider
                            name="パスワード再確認"
                            rules="required|min:8"
                            vid="password_confirmation"
                            v-slot="{errors}"
                        >
                            <input
                                v-model="password_confirmation"
                                name="password_confirmation"
                                type="password"
                                class="form-control top-button"
                                placeholder="パスワード再確認"
                            />
                            <div class="alert alert-danger" v-show="errors[0]">{{errors[0]}}</div>
                        </validation-provider>
                    </div>
                </div>

                <div class="form-group form-button-block">
                    <div class="col-md-6 col-md-offset-4">
                    <button type="submit" class="btn submit-button top-button">登録</button>
                    </div>
                </div>
              </ValidationObserver>
            </div>
        </main>
    </div>
</template>

<script>
import {ValidationProvider, ValidationObserver, extend} from 'vee-validate';
import {required, max, min, email, confirmed} from 'vee-validate/dist/rules';
extend('required', {
    ...required,
    message: '{_field_} is required'
});
extend('email', {
    ...email,
    message: '{_field_} must be mail adress'
});
extend('min', {
    ...min,
    message: '{_field_} must be longer than {length}'
});
extend('max', {
    ...max,
    message: '{_field_} must be shorter thatn {length}'
});
extend('confirmed', {
    ...confirmed,
    message: 'password and password_confirmaion do not match'
});

export default {
    components: {
        ValidationProvider,
        ValidationObserver
    },
    props: ['errors'],
    data: function(){
        return{
            name: "",
            email: "",
            password: "",
            password_confirmation: "",
            csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    },
    methods: {
        async register(){
            const isValid = await this.$refs.observer.validate();
            if(isValid){
                document.querySelector('#register').submit();
            }
        }
    }
}
</script>