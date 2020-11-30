<template>
    <div>
        <template v-if="setting.edited">
            <my-header :name="setting.semester.year + ' ' + setting.semester.type" :auth="auth" bk-color="#919191" />
        </template>
        <template v-else>
            <my-header name="時間割未登録" :auth="auth" bk-color="#919191" />
        </template>
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
        <new-create-setting-modal
            v-show="!setting.edited"
            :setting="setting"
            @register="registerNewInfo"
        />
    </div>
</template>

<script>
import Course from './modules/Course';
import MyHeader from './modules/Header';
import NewCreateSettingModal from './modules/NewCreateSettingModal'

export default {
    components: {
        Course,
        MyHeader,
        NewCreateSettingModal
    },
    data: function(){
        return{
            courses: [],
            dayOfWeek: ['月', '火', '水', '木', '金', '土'],
            periods: [1 ,2, 3, 4, 5, 6],
            setting: {},
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
        getCourses(semesterId){
            axios.get(`api/period/${this.auth.id}?year=${this.setting.semester.year}&type=${this.setting.semester.type}`)
                .then(({data}) => {
                    this.courses = data;
                });
        },
        getSetting(){
            axios.get(`api/user/setting/${this.auth.id}`)
                .then(({data}) => {
                    this.setting = data;
                    if(data.edited){
                        this.getCourses(data.semester.id);
                    }
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
        },
        async registerNewInfo(inputData){
            console.log(inputData)
            let semesterId;
            await axios.post(`/setting/mypage/semester/create/${this.auth.id}`, {
                'year': inputData.semester.year,
                'type': inputData.semester.type,
                'start_date': inputData.semester.start_date,
                'end_date': inputData.semester.end_date
            })
            .then(({data}) => {
                console.log(data)
                semesterId = data.id;
            })
            .catch(err => {
                console.log(err)
            });
            await axios.post(`/setting/mypage/semester/change/${semesterId}`)
            .then(({data}) => {
                console.log(data)
            })
            .catch(err => {
                console.log(err)
            });
            await axios.post(`/setting/${this.auth.id}/register`, {
                first_start_time: inputData.periods[0][0],
                first_end_time: inputData.periods[0][1],
                second_start_time: inputData.periods[1][0],
                second_end_time: inputData.periods[1][1],
                third_start_time: inputData.periods[2][0],
                third_end_time: inputData.periods[2][1],
                fourth_start_time: inputData.periods[3][0],
                fourth_end_time: inputData.periods[3][1],
                fifth_start_time: inputData.periods[4][0],
                fifth_end_time: inputData.periods[4][1],
                sixth_start_time: inputData.periods[5][0],
                sixth_end_time: inputData.periods[5][1],
                university: inputData.university,
                enter_year: inputData.enter_year,
                graduation_year: inputData.graduation_year,
                edited: true
            })
            .then(({data}) => {
                console.log(data)
            })
            .catch(err => {
                console.log(err)
            })
        }
    },
    created(){
        this.getSetting();
    }
}
</script>