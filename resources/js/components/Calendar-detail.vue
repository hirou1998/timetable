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
                        v-for="event in eventsInfo"
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
    props: ['day', 'month', 'year', 'courses', 'assignments', 'events'],
    data: function(){
        return{
            weeks: ['日', '月', '火', '水', '木', '金', '土'],
            setting: {},
            infoVisibility: false,
            modalVisibility: false,
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
            eventForm: {},
            eventsInfo: this.events,
            deleteInfo: {}
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
                console.log(data);
                this.eventsInfo.push(data);
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
                console.log(data);
                this.eventsInfo = this.eventsInfo.filter(event => event.id !== target);
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
                    this.eventsInfo = this.eventsInfo.map(event => {
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
        getDateOfEvent: function(date){
            var item = new Date(date);
            var month = item.getMonth() + 1;
            return item.getFullYear() + '/' + month + '/' + item.getDate();
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