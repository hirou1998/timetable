<template>
    <div class="assignment-calendar-content">
        <p class="assignment-calendar-content-title">{{assignmentInfo.body}}</p>
        <div class="assignment-calendar-done-icon" @click="makeAssignmentDone">
            <i class="fas fa-check" :class="assignmentInfo.done_flg === 0 ? 'non-done' : 'done'"></i>
        </div>
    </div>
</template>

<script>

export default {
    props: ['assignment'],
    data: function(){
        return{
            assignmentInfo: this.assignment
        }
    },
    methods: {
        makeAssignmentDone: function(){
            this.assignmentInfo.done_flg === 0 ? this.assignmentInfo.done_flg = 1 : this.assignmentInfo.done_flg = 0;
            axios.put(`/assignment/${this.assignment.id}`, {
                'done_flg': this.assignmentInfo.done_flg
            }).then(({data}) => {
                console.log(data);
            })
        }
    }
}
</script>