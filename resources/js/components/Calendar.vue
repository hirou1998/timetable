<template>
    <div>
        <my-header :name="currentYear + ' ' + currentMonth + '月'" :auth="auth" />
        <section class="calendar">
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
                <div v-for="(week, index) in weeks" :key="week" class="calendar-item calendar-head" :class="index === 0 ? 'sunday' : ''">
                    <span>{{week}}</span>
                </div>
            </div>
            <div class="d-flex calendar-flex">
                <div 
                    v-for="(day, index) in days" 
                    :key="currentMonth + day + index" 
                    class="calendar-item calendar-day" 
                    :class="space.length > 4 ? 'expanded' : ''"
                    @click="findCourses(index, day)"
                >
                    <span 
                        class="calendar-item-head" 
                        :class="currentYear === todayYear && currentMonth === todayMonth && day === todayDate ? 'current' : ''"
                    >
                        {{day}}
                    </span>
                    <template v-if="findAssignment(currentMonth, day)">
                        <li 
                            v-for="assignment in findAssignment(currentMonth, day)"
                            :key="assignment.id"
                            class="calendar-item-checked" 
                            :style="{backgroundColor: assignment.course.color}"
                        >
                            {{assignment.body}}
                        </li>
                    </template>
                    <template v-if="findEvent(day)">
                        <ul>
                            <li 
                                v-for="event in findEvent(day)"
                                :key="event.id"
                                class="calendar-item-event"
                            ><span :style="{backgroundColor: event.color}"></span>{{event.body}}</li>
                        </ul>
                    </template>
                </div>
            </div>
            <div class="calendar-add">
                <button class="calendar-add-button" @click="toggleModal">
                    <i class="fas fa-plus"></i>
                </button>
            </div>
        </section>
        <calendar-modal
            v-show="modalVisibility"
            @close="toggleModal"
            @save="addEvent"
        />
    </div>
</template>

<script>
import MyHeader from './modules/Header'
import CalendarModal from './modules/Calendar-modal'

export default {
    components: {
        MyHeader,
        CalendarModal
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
            assignments: [],
            courses: [],
            events: [],
            modalVisibility: false,
        }
    },
    methods: {
        addEvent: function(data){
            var form = data;
            axios.post(`/${this.auth.id}/event/`, {
                'title': form.title,
                'isAllday': form.allday,
                'startDay': this.getDateOfEvent(form.startDay),
                'endDay': this.getDateOfEvent(form.endDay),
                'startTime': form.startTime,
                'endTime': form.endTime,
                'color': form.color,
                'location': form.location
            })
            .then(({data}) => {
                console.log(data);
                this.events.push(data);
                this.modalVisibility = false;
            })
            .catch((err) => {
                console.log(err);
            })
        },
        findAssignment: function(month, date){
            return this.assignments.filter(a => a.month === month && a.date === date);
        },
        findEvent: function(date){
            var month;
            String(this.currentMonth).length === 2 ? month = this.currentMonth : month = '0' + this.currentMonth;
            var target = this.currentYear + '-' + month + '-' + date;
            return this.events.filter(e => e.start_day == target);
        },
        findCourses: function(index, day){
            var remainder = index % 7;
            var items = this.courses.filter(c => c.day_of_week === remainder);
            var assignments = this.findAssignment(this.currentMonth, day);
            var events = this.findEvent(day);
            this.$router.push({name: 'calendar-detail', params: {courses: items, day: day, month: this.currentMonth, year: this.currentYear, assignments: assignments, events: events}});
        },
        getAssignments: function(){
            axios.get(`/api/course/assignments?user=${this.auth.id}`)
                .then(({data}) => {
                    var items = data.filter(d => d.done_flg === 1);
                    items.forEach(d => {
                        this.assignments.push({
                            ...d,
                            month: this.getMonth(d.date),
                            date: this.getDate(d.date)
                        })
                    })
                })
        },
        getCourses: function(){
            axios.get(`/api/period/${this.auth.id}`)
                .then(({data}) => {
                    this.courses = data;
                })
        },
        getMonth: function(item){
            var date = new Date(item);
            return date.getMonth() + 1;
        },
        getDate: function(item){
            var date = new Date(item);
            return date.getDate();
        },
        getDateOfEvent: function(date){
            var item = new Date(date);
            var month = item.getMonth() + 1;
            return item.getFullYear() + '/' + month + '/' + item.getDate();
        },
        getEvents: function(year, month){
            this.events = [];
            axios.get(`/api/events/${this.auth.id}?year=${year}&month=${month}`)
            .then(({data}) => {
                this.events.push(...data);
            })
        },
        movePrev: function(){
            if(this.currentMonth === 1){
                this.currentMonth = 12;
                this.currentYear = this.currentYear - 1;
            }else{
                this.currentMonth = this.currentMonth - 1;
            }
            this.getEvents(this.currentYear, this.currentMonth);
        },
        moveNext: function(){
            if(this.currentMonth === 12){
                this.currentMonth = 1;
                this.currentYear = this.currentYear + 1;
            }else{
                this.currentMonth = this.currentMonth + 1;
            }
            this.getEvents(this.currentYear, this.currentMonth);
        },
        toggleModal: function(){
            this.modalVisibility = !this.modalVisibility
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
        },
    },
    async mounted(){
        await this.getAssignments();
        await this.getCourses();

        var date = new Date();
        this.todayYear = date.getFullYear();
        this.todayMonth = date.getMonth() + 1;
        this.todayDate = date.getDate();

        this.getEvents(this.todayYear, this.todayMonth);

        this.currentYear = date.getFullYear();
        this.currentMonth = date.getMonth() + 1;
        this.currentDate = date.getDate();
        this.currentDay = date.getDay();
    }
}
</script>