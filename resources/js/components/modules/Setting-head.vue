<template>
    <div class="setting-head">
        <button class="setting-head-back" @click="backTo">
            <i class="fas fa-chevron-left"></i>
        </button>
        <h2 class="setting-head-title">{{name}}</h2>
    </div>
</template>

<script>
export default {
    props: ['name', 'link'],
    data(){
        return{

        }
    },
    methods: {
        backTo(){
            this.$router.push(`/${this.link}`);
        }
    }
}
</script>