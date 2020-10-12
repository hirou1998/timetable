<template>
    <article>
        <nav class="navbar navbar-light" :style="{backgroundColor: bkColor}">
            <button class="navbar-hamburger" type="button" @click="toggleHamburger">
                <span class="navbar-button-bars navbar-button-icon">
                    <i class="fas fa-bars"></i>
                </span>
            </button>
            <p class="navbar-title">{{name}}</p>
        </nav>
        <hamburger :visibility="hamburgerVisibility" :auth="auth" :page="routeName" :bk-color="bkColor" @delete="deleteCourse" @edit="editCourse" @toggle="toggleHamburger" />
    </article>
</template>

<script>
import Hamburger from './Hamburger';

export default {
    components: {
        Hamburger
    },
    props: ['auth', 'name', 'bkColor'],
    data: function(){
        return{
            hamburgerVisibility: false
        }
    },
    computed: {
        routeName: function(){
            return this.$route.name;
        }
    },
    methods: {
        toggleHamburger: function(){
            this.hamburgerVisibility = !this.hamburgerVisibility;
        },
        deleteCourse: function(){
            this.$emit('delete');
        },
        editCourse: function(){
            this.$emit('edit');
        }
    },
}
</script>