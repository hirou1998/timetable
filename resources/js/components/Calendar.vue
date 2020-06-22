<template>
    <div>
        <my-header :name="currentYear + ' ' + currentMonth + '月'" :auth="auth" />
        <div class="calendar">
            <div class="d-flex calendar-navigation">
                <div @click="movePrev">
                    <button><i class="fas fa-chevron-left"></i></button>
                    {{currentMonth === 1 ? '12' : currentMonth - 1}}月
                </div>
                <div @click="moveNext">
                    {{currentMonth === 12 ? '1' : currentMonth + 1}}月
                    <button><i class="fas fa-chevron-right"></i></button>
                </div>
            </div>
            <div class="d-flex calendar-flex">
                <div v-for="(week, index) in weeks" :key="week" class="calendar-item calendar-head" :class="index === 0 ? 'current' : ''">
                    <span>{{week}}</span>
                </div>
            </div>
            <div class="d-flex calendar-flex">
                <div v-for="(day, index) in days" :key="currentMonth + day + index" class="calendar-item calendar-day" :class="space.length > 4 ? 'expanded' : ''">
                    <span class="calendar-item-head" :class="currentYear === todayYear && currentMonth === todayMonth && day === todayDate ? 'current' : ''">{{day}}</span>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import MyHeader from './modules/Header'

export default {
    components: {
        MyHeader
    },
    data: function(){
        return{
            currentYear: 0,
            currentMonth: 0,
            currentDate: 0,
            currentDay: 0,
            weeks: ['日', '月', '火', '水', '木', '金', '土'],
            todayYear: 0,
            todayMonth: 0,
            todayDate: 0,
            assignments: undefined
        }
    },
    methods: {
        movePrev: function(){
            if(this.currentMonth === 1){
                this.currentMonth = 12;
                this.currentYear = this.currentYear - 1;
            }else{
                this.currentMonth = this.currentMonth - 1;
            }
        },
        moveNext: function(){
            if(this.currentMonth === 12){
                this.currentMonth = 1;
                this.currentYear = this.currentYear + 1;
            }else{
                this.currentMonth = this.currentMonth + 1;
            }
        },
        getAssignments: function(){
            axios.get(`/api/course/assignments?user=${this.auth.id}`)
                .then(({data}) => {
                    this.assignments = data;
                })
        },
    },
    computed: {
        auth: function(){
            return __auth();
        },
        firstDay: function(){
            return new Date(this.currentYear, this.currentMonth - 1, 1).getDay();
        },
        lastDay: function(){
            return new Date(this.currentYear, this.currentMonth, 0).getDate();
        },
        space: function(){
            return [...Array(this.firstDay)].map(i => " ");
        },
        days: function(){
            var dayList = [...Array(this.lastDay)].map((d, i) => i + 1);
            return this.space.concat(dayList);
        }
    },
    async mounted(){
        await this.getAssignments();

        var date = new Date();
        this.todayYear = date.getFullYear();
        this.todayMonth = date.getMonth() + 1;
        this.todayDate = date.getDate();

        this.currentYear = date.getFullYear();
        this.currentMonth = date.getMonth() + 1;
        this.currentDate = date.getDate();
        this.currentDay = date.getDay();
    }
}
</script>