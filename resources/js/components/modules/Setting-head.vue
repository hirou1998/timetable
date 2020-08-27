<template>
    <div class="setting-head">
        <button class="setting-head-back" @click="backTo">
            <i class="fas fa-chevron-left"></i>
        </button>
        <h2 class="setting-head-title">{{name}}</h2>
        <button class="setting-head-edit" @click="edit" v-if="optionButtonVisibility">
            <i class="far fa-edit"></i>
        </button>
    </div>
</template>

<script>
export default {
    props: ['name', 'link', 'optionButtonVisibility'],
    data(){
        return{
        }
    },
    methods: {
        backTo(){
            this.$router.push(`/${this.link}`);
        },
        edit(){
            this.$emit('edit');
        }
    }
}
</script>