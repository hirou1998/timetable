<template>
    <section class="navbar-hamburger-menu" :class="visibility ? 'open' : ''" :style="{backgroundColor: bkColor}">
        <div class="navbar-hamburger-menu-close" @click="toggleHamburger">
            <i class="fas fa-times"></i>
        </div>
        <div class="navbar-hamburger-menu-user navbar-hamburger-menu-big d-flex align-items-center">
            <img src="/images/profile.png" alt="profile-icon" style="width: 10%; margin-right: 1vw;">
            <p class="mb-0 navbar-hamburger-menu-user-name">{{auth.name}}</p>
            <router-link to="/setting"><img src="/images/setting.png" alt="setting-icon" style="width: 50%;"></router-link>
        </div>
        <ul class="navbar-hamburger-menu-items">
            <li class="navbar-hamburger-menu-item" :class="isCurrentPage('timetable') ? 'current' : ''" @click="toggleHamburger">
                <router-link to="/timetable">時間割</router-link>
            </li>
            <li class="navbar-hamburger-menu-item" :class="isCurrentPage('calendar') ? 'current' : ''" @click="toggleHamburger">
                <router-link to="/calendar">カレンダー</router-link>
            </li>
        </ul>
        <div class="navbar-hamburger-menu-logout navbar-hamburger-menu-big d-flex align-items-center" @click="toggleHamburger">
            <i class="fas fa-lock-open"></i>
            <a @click="logout">ログアウト</a>
            <form action="/logout" id="logout-form" method="POST" style="display: none;">
                <input type="hidden" name="_token" :value="csrf">
            </form>
        </div>
    </section>
</template>

<script>
export default {
    props: ['visibility', 'auth', 'page', 'bkColor'],
    data: function(){
        return{
            csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    },
    methods: {
        toggleHamburger: function(){
            this.$emit('toggle');
        },
        isCurrentPage: function(name){
            return name === this.page ? true : false;
        },
        logout: function(){
            document.querySelector('#logout-form').submit();
        },
        deleteCourse: function(){
            this.$emit('delete');
        },
        editCourse: function(){
            this.$emit('edit');
        }
    }
}
</script>