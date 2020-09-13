<template>
    <div>
      <setting-head
        name="マイページ"
        link="setting"
        :option-button-visibility="!isEditing"
        @edit="toggleEditing"
      />
      <div class="setting-body mypage-body">
        <div class="setting-edit-block">
          <div class="setting-edit-block-inner">
            <template v-if="isEditing">
              <button class="btn btn-sm btn-danger" @click="toggleEditing">取消</button>
              <button class="btn btn-sm btn-success" @click="changePeriod">保存</button>
            </template>
          </div>
        </div>

        <div class="mypage-head">
          <h2 class="mypage-name">{{userInfo.name}}</h2>
          <!-- <div>
            <p>早稲田大学 政治経済学部</p>
            <p>2017年度入学</p>
            <p>2021年度3月卒業予定</p>
          </div> -->
        </div>

        <ul class="setting-list-container">
          <li class="setting-list">
            <div class="setting-list-icon">
              <img src="/images/reverse.png" alt="">
            </div>
            <div class="setting-list-name">
              <router-link to="">
                年度/学期切替
              </router-link>
            </div>
          </li>
          <li class="setting-list">
            <div class="setting-list-icon">
              <img src="/images/setting-colored.png" alt="">
            </div>
            <div class="setting-list-name">
              <router-link to="">
                プロフィール詳細編集
              </router-link>
            </div>
          </li>
          <li class="setting-list">
            <div class="setting-list-icon">
              <img src="/images/memo.png" alt="">
            </div>
            <div class="setting-list-name">
              <router-link to="">
                メモ
              </router-link>
            </div>
          </li>
        </ul>
        <!-- <div class="btn-password-reset-container">
          <router-link to="/setting/user/password-reset">
            <button class="btn btn-danger btn-password-reset">パスワードを変更する</button>
          </router-link>
        </div> -->
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