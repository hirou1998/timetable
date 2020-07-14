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
                            >{{event.body}}</li>
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
        <section class="calendar-add-modal" v-show="modalVisibility">
            <div class="calendar-add-modal-inner">
                <div class="calendar-add-modal-inner-main" :data-visibility="colorOptionsVisibility ? 'hidden' : 'visible'">
                    <div class="calendar-add-modal-inner-head calendar-add-modal-inner-block">
                        <button class="calendar-add-modal-inner-cancel" @click="toggleModal">
                            <i class="fas fa-times"></i>
                        </button>
                        <div>
                            <input type="text" class="form-control calendar-add-modal-inner-form" v-model="eventForm.title" placeholder="タイトルを入力">
                        </div>
                        <button class="btn btn-info btn-sm btn-white" @click="addEvent">保存</button>
                    </div>
                    <div class="calendar-add-modal-inner-block">
                        <div class="calendar-add-modal-inner-icon">
                            <i class="far fa-clock"></i>
                        </div>
                        <div style="width: 50%;">
                            <p class="calendar-add-modal-inner-text" style="line-height: 1.4;">終日</p>
                            <el-date-picker
                            v-model="eventForm.startDay"
                            type="date"
                            format="M月d日"
                            @change="changeEndDay">
                            </el-date-picker>
                            <el-date-picker
                            v-model="eventForm.endDay"
                            type="date"
                            format="M月d日">
                            </el-date-picker>
                        </div>
                        <div style="width: 35%;">
                            <el-switch
                            v-model="eventForm.allday"
                            style="marginBottom: .5rem;"
                            />
                            <template v-if="!eventForm.allday">
                                <el-time-select
                                v-model="eventForm.startTime"
                                @change="changeEndTime"
                                :picker-options="timePickerOptions"
                                >
                                </el-time-select>
                                <el-time-select
                                v-model="eventForm.endTime"
                                :picker-options="timePickerOptions"
                                >
                                </el-time-select>
                            </template>
                        </div>
                    </div>
                    <div class="calendar-add-modal-inner-block">
                        <div class="calendar-add-modal-inner-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div style="flex: 7;">
                            <input class="calendar-add-modal-inner-form" type="text" v-model="eventForm.location" placeholder="場所を追加">
                        </div>
                    </div>
                    <div class="calendar-add-modal-inner-block">
                        <div class="calendar-add-modal-inner-icon" style="align-items: center;">
                            <span :style="{backgroundColor: eventForm.color}" class="color-icon"></span>
                        </div>
                        <div style="flex: 7;">
                            色を指定する
                            <button><i class="fas fa-chevron-right" @click="toggleColorOptions"></i></button>
                        </div>
                    </div>
                </div>
                <div class="calendar-add-modal-inner-color" :data-visibility="colorOptionsVisibility ? 'visible' : 'hidden'">
                    <button @click="toggleColorOptions"><i class="fas fa-chevron-left"></i></button>
                </div>
            </div>
        </section>
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
            assignments: [],
            courses: [],
            events: [],
            modalVisibility: false,
            eventForm: {
                title: '',
                allday: false,
                startDay: new Date(),
                startTime: '10:00',
                endDay: new Date(),
                endTime: '11:00',
                location: '',
                color: '#B6ABE4'
            },
            prevStartTime: '10:00',
            timePickerOptions: {
                start: '0:00',
                step: '00:15',
                end: '23:45'
            },
            colorOptionsVisibility: false
        }
    },
    methods: {
        addEvent: function(){
            var form = this.eventForm;
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
                this.modalVisibility = false;
            })
            .catch((err) => {
                console.log(err);
            })
        },
        changeEndTime: function(newValue){
            var prev = this.prevStartTime.split(':');
            var prevToSecond = this.toSeconds(prev[0], prev[1]);
            var current = newValue.split(':');
            var currentToSecond = this.toSeconds(current[0], current[1]);
            var end = this.eventForm.endTime.split(':');
            var endToSecond = this.toSeconds(end[0], end[1]);

            var transition = currentToSecond - prevToSecond;
            endToSecond = endToSecond + transition;
            var result = this.toHourAndMinute(endToSecond);
            this.eventForm.endTime = result.hour + ':' + result.minute;
            this.prevStartTime = newValue;
        },
        changeEndDay: function(newValue){
            var start = new Date(newValue);
            var end = new Date(this.eventForm.endDay);
            if(start.getTime() > end.getTime()){
                this.eventForm.endDay = newValue;
            }
        },
        findAssignment: function(month, date){
            return this.assignments.filter(a => a.month === month && a.date === date);
        },
        findEvent: function(date){
            var month;
            String(this.currentMonth).length === 2 ? month = this.currentMonth : month = '0' + this.currentMonth;
            var target = this.currentYear + '-' + month + '-' + date;
            console.log(target);
            return this.events.filter(e => e.start_day == target);
        },
        findCourses: function(index, day){
            var remainder = index % 7;
            var items = this.courses.filter(c => c.day_of_week === remainder);
            var assignments = this.findAssignment(this.currentMonth, day);
            this.$router.push({name: 'calendar-detail', params: {courses: items, day: day, month: this.currentMonth, year: this.currentYear, assignments: assignments}});
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
        toggleColorOptions: function(){
            this.colorOptionsVisibility = !this.colorOptionsVisibility;
        },
        toggleModal: function(){
            this.modalVisibility = !this.modalVisibility
        },
        toSeconds: function(hour, minute){
            return Number(hour) * 3600 + Number(minute) * 60;
        },
        toHourAndMinute: function(second){
            var hour = Math.floor(Math.abs(second) / 3600);
            var minute = Math.floor(Math.abs(second) % 3600 / 60);
            String(hour).length === 1 ? hour = '0' + hour : hour = hour;
            minute === 0 ? minute = '00' : minute = minute;
            return {
                hour: hour,
                minute, minute
            }
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