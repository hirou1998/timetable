<template>
    <div class="modal-base">
        <transition name="slide">
            <section class="detail-option-window" v-show="visibility">
                <ul class="detail-options detail-option-block">
                    <li class="detail-option" @click="editCourse">授業内容を編集</li>
                    <li class="detail-option" @click="colorEdit">授業カラーを編集</li>
                    <li class="detail-option text-red" @click="deleteCourse">この時間割を消す</li>
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
        close(){
            this.$emit('close');
        },
        colorEdit(){
            this.$router.push('/setting/color');
        },
        deleteCourse: function(){
            this.$emit('delete');
            this.close();
        },
        editCourse: function(){
            this.$emit('edit');
            this.close();
        }
    }
}
</script>