<template>
    <div class="assignment-modal" @click="close">
        <div class="assignment-modal-close"><i class="fas fa-times"></i></div>
        <div class="assignment-modal-body" @click.stop>
            <template v-if="!isEditing">
                <assignment-block v-if="modalAssignment.body" :assignment="modalAssignment" :date="modalAssignment.date" is-next-day="" />
                <div v-if="modalAssignment.date" class="assignment-modal-body-memo-container">
                    <h3 class="assignment-modal-body-memo-title">MEMO</h3>
                    <p class="assignment-modal-body-memo-content">{{modalAssignment.memo}}</p>
                </div>
            </template>
            <template v-else>
                <p>{{modalAssignment.date}}</p>
                <div class="form-group">
                    <label for="modal-assignment-name">タイトル</label>
                    <input id="modal-assignment-name" type="text" class="form-control" v-model="formItem.body">
                </div>
                <div class="form-group">
                    <label for="modal-assignment-memo">メモ</label>
                    <textarea name="memo" id="modal-assignment-memo" cols="20" rows="5" v-model="formItem.memo" class="form-control"></textarea>
                </div>
                <div class="form-group d-flex align-items-center">
                    <input type="radio" id="yes" value="1" v-model="formItem.done_flg">
                    <label for="yes" class="radio-title">カレンダーに反映する</label>
                    <input type="radio" id="no" value="0" v-model="formItem.done_flg">
                    <label for="no" class="radio-title">カレンダーに反映しない</label>
                </div>
            </template>
            <div class="d-flex">
                <button v-if="modalAssignment.body" class="btn btn-danger btn-modal">削除</button>
                <button v-if="!isEditing && modalAssignment.body" class="btn btn-info btn-modal" @click="isEditing = true">編集</button>
                <button class="btn btn-info btn-modal" v-else @click="save">保存</button>
            </div>
        </div>
    </div>
</template>

<script>
import AssignmentBlock from './Assignment-block';

export default {
    components: {
        AssignmentBlock
    },
    props: ['modalAssignment'],
    data: function(){
        return{
            formItem: this.modalAssignment,
            isEditing: false
        }
    },
    methods: {
        close: function(){
            this.$emit('close');
        },
        save: function(){
            axios.put(`/assignment/${this.modalAssignment.id}`, {
                'body': this.formItem.body,
                'memo': this.formItem.memo,
                'done_flg': this.formItem.done_flg
            }).then((res) => {
                this.isEditing = false;
                this.$emit('update', res.data);
            })
        }
    }
}
</script>