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
                                    <router-link :to="'/timetable/detail?course=' + course.course.id + '&day=' + course.day_of_week + '&period=' + course.period">{{course.course.name}}</router-link>
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
                        @open="toggleModal"
                        @remove="toggleDeleteModal"
                    />
                </ul>
            </section>
        </div>
        <calendar-modal
            v-show="modalVisibility"
            v-model="eventForm"
            @close="toggleModal({})"
            @save="editEvent"
        />
        <delete-modal
            v-show="deleteModalVisibility"
            :info="deleteInfo"
            type="event"
            @close="deleteModalVisibility = false"
            @delete="deleteEvent"
        />
        <event-add-button 
            @open="toggleModal"
        />
    </div>
</template>

<script>
import CalendarItem from './modules/Calendar-item'
import CalendarCheckedItem from './modules/Calendar-checked-item'
import CalendarModal from './modules/Calendar-modal'
import DeleteModal from './modules/Delete-modal'
import EventAddButton from './modules/Event-add-button'
import MyHeader from './modules/Header'

export default {
    components: {
        CalendarItem,
        CalendarCheckedItem,
        CalendarModal,
        DeleteModal,
        EventAddButton,
        MyHeader,
    },
    data: function(){
        return{
            assignments: [],
            courses: [],
            day: '',
            deleteModalVisibility: false,
            defaultEventForm: {
                title: '',
                allday: false,
                startDay: this.today,
                startTime: '10:00',
                endDay: this.today,
                endTime: '11:00',
                location: '',
                color: '#B6ABE4'
            },
            deleteInfo: {},
            events: {},
            eventForm: {},
            infoVisibility: false,
            modalVisibility: false,
            month: '',
            remainder: '',
            setting: {},
            weeks: ['日', '月', '火', '水', '木', '金', '土'],
            year: '',
        }
    },
    computed: {
        auth: function(){
            return __auth();
        },
        dayOfWeek: function(){
            return this.weeks[this.today.getDay()];
        },
        today(){
            var date = new Date(this.year, this.month - 1, this.day);
            return date;
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
                this.events.push(data);
                this.modalVisibility = false;
            })
            .catch((err) => {
                console.log(err);
            })
        },
        deleteEvent(){
            var target = this.deleteInfo.id
            axios.delete(`/${this.auth.id}/event/${target}`)
            .then((data) => {
                this.events = this.events.filter(event => event.id !== target);
                this.deleteModalVisibility = false;
                this.deleteInfo = {};
            })
        },
        editEvent(data){
            var form = data;
            if(form.id){
                axios.put(`/${this.auth.id}/event/${form.id}`, {
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
                    this.events = this.events.map(event => {
                        if(event.id === data.id){
                            return data
                        }else{
                            return event
                        }
                    });
                    this.modalVisibility = false;
                })
                .catch((err) => {
                    console.log(err);
                })
            }else{
                this.addEvent(form);
            }
        },
        getAssignments: function(){
            axios.get(`/api/course/assignments/u/${this.auth.id}/?year=${this.year}&month=${this.month}&day=${this.day}`)
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
            axios.get(`/api/period/${this.auth.id}/?day_of_week=${this.remainder}`)
                .then(({data}) => {
                    this.courses = data;
                    !this.courses.length ? this.infoVisibility = false : this.infoVisibility = true;
                })
        },
        getEvents: function(year, month, day){
            this.events = [];
            axios.get(`/api/events/${this.auth.id}?year=${year}&month=${month}&day=${day}`)
            .then(({data}) => {
                this.events.push(...data);
            })
        },
        getDateOfEvent: function(date){
            var item = new Date(date);
            var month = item.getMonth() + 1;
            return item.getFullYear() + '/' + month + '/' + item.getDate();
        },
        getMonth: function(item){
            var date = new Date(item);
            return date.getMonth() + 1;
        },
        getDate: function(item){
            var date = new Date(item);
            return date.getDate();
        },
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
        splitParams: function(param){
            param = param.split('&');
            var obj = {};
            for(var i = 0; i < param.length; i++){
                var item = param[i].split('=');
                obj[item[0]] = item[1];
            }
            return obj;
        },
        toggleInfoVisibility: function(){
            this.infoVisibility = !this.infoVisibility;
        },
        toggleModal(info){
            if(info){
                this.eventForm = info;
                this.modalVisibility = !this.modalVisibility;
            }else{
                this.defaultEventForm.startDay = this.today;
                this.defaultEventForm.endDay = this.today;
                this.eventForm = this.defaultEventForm;
                this.modalVisibility = !this.modalVisibility;
            }
        },
        toggleDeleteModal(info){
            this.deleteModalVisibility = !this.deleteModalVisibility;
            this.deleteInfo = info;
        }
    },
    async mounted(){
        await this.getSetting();
        const params = await this.splitParams(location.search.substring(1));

        if(!params.month || !params.year){
            this.$router.push('/calendar');
            return;
        }

        this.month = params.month;
        this.year = params.year;
        this.day = params.day;
        this.currentMonth = this.month;
        this.remainder = params.remainder;

        this.getEvents(this.year, this.month, this.day);
        this.getCourses();
        this.getAssignments();

    }
}
</script>