<template>
    <li @click="changeColor">
        <div class="setting-list-icon">
            <div class="setting-list-icon-circle" :style="{backgroundColor: item.course.color}"></div>
        </div>
        <div class="setting-list-name">{{item.course.name}}</div>
    </li>
</template>

<script>
export default {
    props: ['item'],
    methods: {
        changeColor(){
            this.$emit('open', {color: this.item.course.color, id: this.item.course.id});
        },
    }
}
</script>