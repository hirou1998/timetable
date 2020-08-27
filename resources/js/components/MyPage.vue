<template>
    <div>
        <setting-head
            name="ユーザー情報"
            link="setting"
            :option-button-visibility="!isEditing"
			@edit="toggleEditing"
        />
        <div class="setting-body">
            <div class="setting-edit-block">
				<div class="setting-edit-block-inner">
					<template v-if="isEditing">
						<button class="btn btn-sm btn-danger" @click="toggleEditing">取消</button>
						<button class="btn btn-sm btn-success" @click="changePeriod">保存</button>
					</template>
				</div>
			</div>
            <ul class="setting-list-container">
                <li class="setting-list setting-list-name">
                    <div class="user-info-item">ユーザー名</div>
                    <template>
                        <div class="user-info" v-if="!isEditing">{{userInfo.name}}</div>
                        <input type="text" class="form-control user-info" ref="username" :value="userInfo.name" v-else>
                    </template>
                </li>
                <li class="setting-list setting-list-name">
                    <div class="user-info-item">メールアドレス</div>
                    <template>
                        <div class="user-info" v-if="!isEditing">{{userInfo.email}}</div>
                        <input type="text" class="form-control user-info" ref="email" :value="userInfo.email" v-else>
                    </template>
                </li>
            </ul>
            <div class="btn-password-reset-container">
                <router-link to="/setting/user/password-reset">
                    <button class="btn btn-danger btn-password-reset">パスワードを変更する</button>
                </router-link>
            </div>
        </div>
    </div>
</template>

<script>
import SettingHead from './modules/Setting-head'

export default {
    components: {
        SettingHead
    },
    data(){
        return {
            userInfo: {},
            isEditing: false
        }
    },
    methods: {
        getUserInfo(){
            axios.get('/api/user')
            .then(({data}) => {
                this.userInfo = data
            })
        },
        toggleEditing(){
            this.isEditing = !this.isEditing
        }
    },
    mounted(){
        this.getUserInfo();
    }
}
</script>