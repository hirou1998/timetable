<template>
    <li class="assignment-calendar-item" @click="addAssignment(date)">
        <span :class="isNextDay ? 'next' : 'non-next'">{{date}}</span>
        <assignment v-if="assignment" :assignment="assignment" />
    </li>
</template>

<script>
import Assignment from './modules/Assignments';

export default {
    components: {
        Assignment
    },
    props: ['assignment', 'isNextDay', 'date'],
    methods: {
        addAssignment: function(date){
            this.$emit('add', date);
        }
    }
}
</script>