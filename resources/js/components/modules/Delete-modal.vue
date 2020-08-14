<template>
    <div class="delete-modal-overlay">
        <div class="delete-modal-content">
            <template v-if="type == 'event'">
                {{info.body}}
            </template>
            <template v-if="type == 'course'">
                {{info.name}}
            </template>
            を削除してもよろしいですか？
            <div class="delete-modal-buttons">
                <button class="btn btn-danger btn-sm" @click="deleteEvent">削除</button>
                <button class="btn btn-success btn-sm" @click="closeModal">キャンセル</button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['info', 'type'],
    methods: {
        closeModal(){
            this.$emit('close')
        },
        deleteEvent(){
            this.$emit('delete')
        }
    }
}
</script>