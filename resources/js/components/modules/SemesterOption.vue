<template>
    <div class="modal-base">
        <transition name="slide">
            <section class="detail-option-window" v-show="visibility">
                <ul class="detail-options detail-option-block">
                    <li class="detail-option" @click="editSemester">学期内容を編集</li>
                    <li class="detail-option text-red" @click="deleteSemester">この学期を削除する</li>
                </ul>
                <div class="detail-option detail-option-block" @click="close">取消</div>
            </section>
        </transition>
    </div>
</template>

<script>
export default {
    props: ['visibility'],
    methods: {
        editSemester(){
            this.$emit('edit');
        },
        deleteSemester(){
            this.$emit('delete');
        },
        close(){
            this.$emit('close');
        }
    }
}
</script>