<template>
    <div>
      <setting-head
        :name="auth.name"
        link="timetable"
      />
      <div class="setting-body">
        <h3 class="setting-subtitle">カスタマイズ</h3>
        <ul class="setting-list-container">
          <li class="setting-list">
            <div class="setting-list-icon">
              <img src="/images/color.png" alt="カラーアイコン">
            </div>
            <div class="setting-list-name"><router-link to="/setting/color">授業カラー</router-link></div>
          </li>
          <li class="setting-list">
            <div class="setting-list-icon">
              <img src="/images/course-period.png" alt="時限数と授業時間">
            </div>
            <div class="setting-list-name"><router-link to="/setting/period">時限数と授業時間</router-link></div>
          </li>
        </ul>
        <ul class="setting-list-container mt-5">
          <li class="setting-list">
            <div class="setting-list-icon">
              <img src="/images/profile-colored.png" alt="ユーザー情報">
            </div>
            <div class="setting-list-name">
              <router-link to="/setting/mypage">マイページ</router-link>
            </div>
          </li>
        </ul>
      </div>
    </div>
</template>

<script>
import SettingHead from './modules/Setting-head'

export default {
    components: {
        SettingHead
    },
    methods: {
    },
    computed: {
        auth(){
            return __auth();
        }
    }
}
</script>