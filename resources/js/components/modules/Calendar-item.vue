<template>
    <li class="calendar-event-item calendar-detail-item">
        <h3 class="calendar-event-item-title">
            <span :style="{backgroundColor: color}"></span>
            {{body}}
        </h3>
        <p class="calendar-event-item-text">{{getDate}} {{getStartTime}}-{{getEndTime}}</p>
        <p class="calendar-event-item-text">
            <img src="/images/location.png" alt="" class="calendar-event-item-icon">
            {{location}}
        </p>
        <div class="calendar-event-item-edit">
            <img src="/images/edit-event.png" alt="">
        </div>
    </li>
</template>

<script>
export default {
    props: ['event'],
    computed: {
        getDate(){
            var date = new Date(this.start);
            return date.getMonth() + 1 + '月' + date.getDate() + '日';
        },
        getEndTime(){
            if(this.endTime){
                var time = this.endTime.split(':');
                return time[0] + ':' + time[1]
            }else{
                return
            }
        },
        getStartTime(){
            if(this.startTime){
                var time = this.startTime.split(':');
                return time[0] + ':' + time[1]
            }else{
                return
            }
        },
        body: {
            get(){
                return this.event.body
            }
        },
        color: {
            get(){
                return this.event.color
            }
        },
        endTime: {
            get(){
                return this.event.end_time
            }
        },
        location: {
            get(){
                return this.event.location
            }
        },
        start: {
            get(){
                return this.event.start_day
            }
        },
        startTime: {
            get(){
                return this.event.start_time
            }
        },
    },
    methods: {
        
    }
}
</script>