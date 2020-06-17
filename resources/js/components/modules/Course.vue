<template>
    <div class="course-panel-card" @click="seeDetail">
        <div class="course-panel-color-label" :style="{'backgroundColor': course.course.color}"></div>
        <p class="course-panel-name">{{course.course.name}}</p>
    </div>
</template>

<script>
export default {
    props: ['course'],
    methods: {
        async seeDetail(){
            await this.$store.dispatch('course/set', this.course.course);

            this.$router.push(`/timetable/detail?course=${this.course.course_id}&day=${this.course.day_of_week}&period=${this.course.period}`);
        }
    }
}
</script>