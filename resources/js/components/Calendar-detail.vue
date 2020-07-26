<template>
    <div>
        <my-header :name="year + ' ' + month + '月'" :auth="auth" />
        <div class="calendar-detail-body">
            <section class="calendar-detail-head">
                <h2 
                    class="calendar-detail-head-title"
                    @click="toggleInfoVisibility"
                >
                    {{month}}月{{day}}日({{dayOfWeek}})
                    <template>
                        <i class="fas fa-angle-down" v-if="!infoVisibility" ></i>
                        <i class="fas fa-angle-up" v-else></i>
                    </template>
                </h2>
                <div class="calendar-detail-info" v-if="infoVisibility">
                    <ul>
                        <li
                            v-for="(course, course_index) in courses" :key="course.course_id"
                        >
                            <div class="d-flex mb-1">
                                <p class="calendar-detail-info-period">
                                    {{course.period}}限
                                    (<template v-for="(time, index) in getPeriodTime()[course_index]">{{time}}<template v-if="index === 0">~</template>
                                    </template>)
                                </p>
                                <p class="calendar-detail-info-course">
                                    <span class="calendar-detail-info-course-block" :style="{backgroundColor: course.course.color}"></span>
                                    {{course.course.name}}
                                </p>
                            </div>
                        </li>
                    </ul>
                </div>
            </section>
            <section class="calendar-item-body">
                <ul>
                    <calendar-checked-item
                        v-for="assignment in assignments"
                        :key="assignment.id"
                        :assignment="assignment"
                        :style="{backgroundColor: assignment.course.color}"
                    /> 
                </ul>
                <ul>
                    <calendar-item 
                        v-for="event in events"
                        :key="event.id"
                        :event="event"
                    />
                </ul>
            </section>
        </div>
    </div>
</template>

<script>
import CalendarItem from './modules/Calendar-item'
import CalendarCheckedItem from './modules/Calendar-checked-item'
import MyHeader from './modules/Header'

export default {
    components: {
        CalendarItem,
        CalendarCheckedItem,
        MyHeader
    },
    props: ['day', 'month', 'year', 'courses', 'assignments', 'events'],
    data: function(){
        return{
            weeks: ['日', '月', '火', '水', '木', '金', '土'],
            setting: {},
            infoVisibility: false
        }
    },
    computed: {
        auth: function(){
            return __auth();
        },
        dayOfWeek: function(){
            var date = new Date(this.year, this.month - 1, this.day);
            return this.weeks[date.getDay()];
        },
    },
    methods: {
        getSetting: function(){
            axios.get(`/api/user/setting/${this.auth.id}`).then(({data}) => {
                this.setting = data;
            })
        },
        getPeriodTime: function(){
            return this.courses.map(course => {
                return this.setting.periods[course.period - 1];
            });
        },
        toggleInfoVisibility: function(){
            this.infoVisibility = !this.infoVisibility;
        }
    },
    async mounted(){
        if(!this.day || !this.month){
            this.$router.push('/calendar');
        }else{
            await this.getSetting();
            this.currentMonth = this.month;
            !this.courses.length ? this.infoVisibility = false : this.infoVisibility = true;
        }
    }
}
</script>