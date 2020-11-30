<template>
    <div class="modal-base">
        <section class="semester-edit-window">
            <div class="semester-edit-inner">
                <semester-form v-model="semester" :types="types" />
            </div>
            <div class="d-flex justify-content-space-around">
                <button class="btn btn-sm btn-danger" @click="cancelEdit">キャンセル</button>
                <button class="btn btn-sm btn-success" @click="save">変更を保存</button>
            </div>
        </section>
    </div>
</template>

<script>
import SemesterForm from './SemesterForm'

export default {
    components: {
        SemesterForm
    },
    props: ['semester', 'types'],
    methods: {
        cancelEdit(){
            this.$emit('cancel');
        },
        save(){
            if(this.semester.id){
                this.$emit('edit');
            }else{
                this.$emit('add');
            }
        },
    }
}
</script>