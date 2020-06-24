<template>
    <div class="assignment-modal" @click="close">
        <div class="assignment-modal-close"><i class="fas fa-times"></i></div>
        <div class="assignment-modal-body" @click.stop>
            <template v-if="!isEditing">
                <assignment-block v-if="!isAdding" :assignment="modalAssignment" :date="modalAssignment.date" is-next-day="" />
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
                <button v-if="!isAdding" class="btn btn-danger btn-modal" @click="deleteItem">削除</button>
                <button v-if="!isEditing && !isAdding" class="btn btn-info btn-modal" @click="isEditing = true">編集</button>
                <button class="btn btn-info btn-modal" v-if="isEditing && !isAdding" @click="save">保存</button>
                <button class="btn btn-success btn-modal" v-if="isAdding" @click="register">登録</button>
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
    props: ['assignment'],
    data: function(){
        return{
            modalAssignment: this.assignment,
            formItem: this.assignment,
            isEditing: false, 
            isAdding: false,
        }
    },
    computed: {
        courseId: function(){
            return this.$route.query.course;
        },
        dateInThisYear: function(){
            var today = new Date();
        }
    },
    methods: {
        close: function(){
            this.$emit('close');
            this.isAdding = false;
            this.isEditing = false;
        },
        save: function(){
            axios.put(`/${this.courseId}/assignment/${this.modalAssignment.id}`, {
                'body': this.formItem.body,
                'memo': this.formItem.memo,
                'done_flg': this.formItem.done_flg
            }).then((res) => {
                this.isEditing = false;
                this.$emit('update', res.data);
            })
        },
        deleteItem: function(){
            axios.delete(`/${this.courseId}/assignment/${this.modalAssignment.id}`)
            .then((res) => {
                this.$emit('close');
                this.$emit('filter', this.modalAssignment.id);
            });
        },
        register: function(){
            axios.post(`/${this.courseId}/assignment`, {
                'body': this.formItem.body,
                'memo': this.formItem.memo,
                'done_flg': this.formItem.done_flg,
                'date': this.formatDate(this.formItem.date),
            }).then(({data}) => {
                this.isEditing = false;
                this.isAdding = false;
                this.modalAssignment = {
                    ...data,
                    date: this.getDateOfAssignment(data.date)
                }
                this.$emit('add', data);
            })
        },
        getDateOfAssignment: function(date){
            var item = new Date(date);
            return item.getMonth() + 1 + '/' + item.getDate();
        },
        formatDate: function(date){
            var today = new Date();
            var year = today.getFullYear();
            date = new Date(date);
            var month = date.getMonth() + 1;
            var day = date.getDate();
            return year + '-' + month + '-' + day;
        }
    }
}
</script>