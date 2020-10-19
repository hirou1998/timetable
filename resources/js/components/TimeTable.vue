<template>
    <div>
        <my-header :name="setting.semester.year + ' ' + setting.semester.type" :auth="auth" bk-color="#919191" />
        <table class="course">
            <tr class="course-day-head">
                <th></th>
                <th 
                    v-for="(day, index) in dayOfWeek" :key="day"
                    class="course-day-head-panel"
                >
                    <span :class="today === index ? 'current' : ''">{{day}}</span>
                </th>
            </tr>
            <tr 
                v-for="period in periods" :key="period"
            >
                <td class="course-period-panel">{{period}}</td>
                <template v-for="(day, day_index) in dayOfWeek">
                    <td 
                        v-if="existsCourse(day_index + 1, period)" 
                        :key="day + period" 
                        class="course-panel">
                        <course :course="findCourse(day_index + 1, period)" />
                    </td>
                    <td 
                        v-else 
                        :key="day + period"  
                        @click="registerCourse(day_index + 1, period)"
                        class="course-panel"></td>
                </template>
            </tr>
        </table>
    </div>
</template>

<script>
import Course from './modules/Course';
import MyHeader from './modules/Header';

export default {
    components: {
        Course,
        MyHeader
    },
    data: function(){
        return{
            courses: [],
            dayOfWeek: ['月', '火', '水', '木', '金', '土'],
            periods: [1 ,2, 3, 4, 5, 6],
            setting: {}
        }
    },
    computed: {
        auth: function(){
            return __auth();
        },
        today: function(){
            return new Date().getDay();
        },
    },
    methods: {
        async getCourses(semesterId){
            axios.get(`api/period/${this.auth.id}/${semesterId}`)
                .then(({data}) => {
                    this.courses = data;
                });
        },
        async getSetting(){
            axios.get(`api/user/setting/${this.auth.id}`)
                .then(({data}) => {
                    this.setting = data;
                    this.getCourses(data.semester.id);
                });
        },
        findCourse: function(day_index, period){
            return this.courses.find(course => course.day_of_week === day_index && course.period === period);
        },
        existsCourse: function(day_index, period){
            return this.findCourse(day_index, period) ? true : false;
        },
        registerCourse: function(day, period){
            this.$router.push(`/timetable/detail?course=&day=${day}&period=${period}`);
        }
    },
    mounted(){
        this.getSetting();
    }
}
</script>