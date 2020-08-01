<template>
    <div class="calendar-add">
        <button class="calendar-add-button" @click="openModal">
            <i class="fas fa-plus"></i>
        </button>
    </div>
</template>

<script>
export default {
    methods: {
        openModal(){
            this.$emit('open')
        }
    }
}
</script>