<template>
    <div class="course-content-body">
        {{periodList}}
        <ul>
            <li></li>
        </ul>
    </div>
</template>

<script>

export default {
    props: ['setting', 'weekOfDay', 'course'],
    data: function(){
        return{
            courseInfo: this.course
        }
    },
    computed: {
        periodList: function(){
            this.course.periods.forEach(p => {
                return p.day_of_week;
            })
        },
    },
    methods: {
        dateList: function(){
            var end = new Date(this.setting.end_date);
            var start = new Date(this.setting.start_date);
            var diff = (end.getTime() - start.getTime())/ (1000*60*60*24);
            var startDay = start.getDay();
            var endDay = end.getDay();
            var dateListArray = [];
            for(var i = startDay; i < diff; i++){
                console.log(i);
                // for(var j = 0; j < this.course.periods.length; j++){
                //     if(i === this.course.periods[j].day_of_week + (7 * i)){
                //         var date = start.setDate(start.getDate() + pList[j] + (7 * i));
                //         console.log(this.course.periods[j].day_of_week + (7 * i));
                //     }
                // }
            }
        },
    },
    created: function(){
        console.log(this.course);
    }
}
</script>