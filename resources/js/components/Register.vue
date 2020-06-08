<template>
    <div>
        <main>
            <div class="container">
                <div class="row justify-content-center">
                <div class="col-md-8 col-md-offset-2">
                    <div class="card mt-3">
                    <div class="card-header text-center">ユーザー登録</div>

                    <!-- <div class="form-group has-error" v-if="errors.length !== 0">
                        <div class="alert alert-danger text-center">
                            Errors happened while registrating a user
                            <div v-for="(error, key, index) in errors" :key="index">{{key}}: {{error}}</div>
                        </div>
                    </div> -->

                    <div class="card-body">
                        <ValidationObserver
                        class="form-horizontal"
                        ref="observer"
                        action="/register"
                        id="register"
                        method="post"
                        tag="form"
                        @submit.prevent="register()"
                        v-slot="{ invalid }"
                        >
                        <input type="hidden" name="_token" :value="csrf">
                        <div class="form-group">
                            <label for="name" class="col-md-4 control-label">名前</label>

                            <div class="col-md-6">
                                <validation-provider name="名前" rules="required|max:20" v-slot="{errors}">
                                    <input v-model="name" name="name" type="text" class="form-control" />
                                    <div class="alert alert-danger" v-show="errors[0]">{{errors[0]}}</div>
                                </validation-provider>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="email" class="col-md-4 control-label">メールアドレス</label>

                            <div class="col-md-6">
                                <validation-provider
                                    name="メールアドレス"
                                    rules="required|email"
                                    v-slot="{errors}"
                                >
                                    <input v-model="email" name="email" type="email" class="form-control" />
                                    <div class="alert alert-danger" v-show="errors[0]">{{errors[0]}}</div>
                                </validation-provider>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password" class="col-md-4 control-label">パスワード</label>

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
                                        class="form-control"
                                    />
                                    <div class="alert alert-danger" v-show="errors[0]">{{errors[0]}}</div>
                                </validation-provider>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password-confirm" class="col-md-4 control-label">パスワード再確認</label>

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
                                        class="form-control"
                                    />
                                    <div class="alert alert-danger" v-show="errors[0]">{{errors[0]}}</div>
                                </validation-provider>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                            <button type="submit" class="btn btn-primary">登録</button>
                            </div>
                        </div>
                        </ValidationObserver>
                    </div>
                    </div>
                </div>
                </div>
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