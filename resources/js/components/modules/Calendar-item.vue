<template>
    <li class="calendar-event-item calendar-detail-item">
        <h3 class="calendar-event-item-title">
            <span :style="{backgroundColor: event.color}"></span>
            {{event.body}}
        </h3>
        <p class="calendar-event-item-text">
            {{getDate}} <template v-if="!event.is_allday">{{getStartTime}}-{{getEndTime}}</template>
        </p>
        <p class="calendar-event-item-text">
            <img src="/images/location.png" alt="" class="calendar-event-item-icon">
            {{event.location}}
        </p>
        <div class="calendar-event-item-edit">
            <img src="/images/edit-event.png" alt="" @click="toggleModal">
            <i class="far fa-trash-alt i-normal" @click="deleteEvent"></i>
        </div>
    </li>
</template>

<script>

export default {
    props: ['event'],
    data(){
        return{
            
        }
    },
    computed: {
        getDate(){
            var date = new Date(this.event.start_day);
            return date.getMonth() + 1 + '月' + date.getDate() + '日';
        },
        getEndTime(){
            if(this.event.end_time){
                var time = this.event.end_time.split(':');
                return time[0] + ':' + time[1]
            }else{
                return ''
            }
        },
        getStartTime(){
            if(this.event.start_time){
                var time = this.event.start_time.split(':');
                return time[0] + ':' + time[1]
            }else{
                return ''
            }
        },
    },
    methods: {
        deleteEvent(){
            this.$emit('remove', this.event)
        },
        toggleModal(){
            var event = this.event;
            event.is_allday == 0 ? event.is_allday = false : event.is_allday = true;
            var info = {
                id: event.id,
                title: event.body,
                allday: event.is_allday,
                startDay: event.start_day,
                startTime: event.start_time,
                endDay: event.end_day,
                endTime: event.end_time,
                location: event.location,
                color: event.color
            };
            this.$emit('open', info)
        }
    }
}
</script>