<template>
    <div>
        <table class="course">
            <tr class="course-day-head">
                <th></th>
                <th 
                    v-for="day in dayOfWeek" :key="day"
                    class="course-day-head-panel"
                >{{day}}</th>
            </tr>
            <tr 
                v-for="period in periods" :key="period"
            >
                <td class="course-period-panel">{{period}}</td>
                <template v-for="(day, day_index) in dayOfWeek">
                    <td 
                        v-if="exitsCourse(day_index, period)" 
                        :key="day + period" 
                        class="course-panel">
                        <course :course="findCourse(day_index, period)" />
                    </td>
                    <td 
                        v-else 
                        :key="day + period"  
                        @click="registerCourse(day_index, period)"
                        class="course-panel"></td>
                </template>
            </tr>
        </table>
    </div>
</template>

<script>
import Course from './modules/Course';

export default {
    components: {
        Course
    },
    data: function(){
        return{
            dayOfWeek: ['月', '火', '水', '木', '金', '土'],
            periods: [1 ,2, 3, 4, 5, 6],
            courses: [],
        }
    },
    computed: {
        auth: function(){
            return __auth();
        }
    },
    methods: {
        findCourse: function(day_index, period){
            return this.courses.find(course => course.day_of_week === day_index + 1 && course.period === period);
        },
        exitsCourse: function(day_index, period){
            return this.findCourse(day_index, period) ? true : false;
        },
        registerCourse: function(day, period){
            this.$router.push(`/timetable/detail?course=&day=${day}&period=${period}`);
        }
    },
    mounted(){
        //console.log(this.auth);
        axios.get(`/api/period/${this.auth.id}`)
            .then(({data}) => {
                this.courses = data;
            })
    }
}
</script>