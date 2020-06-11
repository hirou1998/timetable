<template>
    <div class="course-detail">
        <h2 class="course-detail-title" :style="{'backgroundColor': course.color}">
            <span>{{getWeekOfDay(periodInfo.day_of_week)}}曜{{periodInfo.period}}限</span>
            {{course.name}}
        </h2>
        <div class="course-detail-body">
            <h3 class="course-detail-body-title" @click="toggleInfo" v-if="!isEditing">
                {{course.name}}
                <template>
                    <i class="fas fa-angle-down" v-if="!isInfoOpen" ></i>
                    <i class="fas fa-angle-up" v-else></i>
                </template>
            </h3>
            <div class="form-group" v-else>
                <input type="text" class="form-control" v-model="formData.name" placeholder="科目名">
            </div>
            <div class="course-detail-body-info" v-if="isInfoOpen">
                <button class="btn btn-info btn-sm course-detail-body-info-edit" @click="edit" v-if="!isEditing">EDIT</button>
                <button class="btn btn-info btn-sm course-detail-body-info-edit" @click="save" v-else>SAVE</button>
                <button class="btn btn-danger btn-sm course-detail-body-info-cancel" @click="edit" v-if="isEditing && isAlreadyRegistered">CANCEL</button>
                <div class="course-detail-body-info-item">
                    <img src="/images/teacher-icon.png" alt="">
                    <p class="course-detail-body-text" v-if="!isEditing">{{course.teacher}} 先生</p>
                    <div class="form-group" v-else>
                        <input type="text" class="form-control" v-model="formData.teacher" placeholder="先生の名前">
                    </div>
                </div>
                <div class="course-detail-body-info-item">
                    <img src="/images/period-icon.png" alt="">
                    <div>
                        <p class="course-detail-body-text" v-if="!isEditing">{{course.type}}</p>
                        <div class="form-group" v-else>
                            <select v-model="formData.type" class="form-control" data-live-search="true">
                                <option v-for="type in courseType" :value="type" :key="type">{{type}}</option>
                            </select>
                        </div>
                        <ul v-if="!isEditing">
                            <li class="course-detail-body-info-block" v-for="period in course.periods" :key="period.id">
                                <p class="course-detail-body-text" style="margin-right: 4vw;">{{getWeekOfDay(period.day_of_week)}}曜</p>
                                <p class="course-detail-body-text">
                                    {{period.period}}限
                                    (
                                        <template v-for="(item, index) in getPeriodTime(period.period - 1)">
                                            {{item}}
                                            <template v-if="index === 0">~</template>
                                        </template>
                                    )
                                </p>
                            </li>
                        </ul>
                        <ul v-else>
                            <li class="form-group course-detail-body-info-select-flex" v-for="period in formData.periods" :key="period.day_of_week + '-' + period.period">
                                <select v-model="period.day_of_week" class="form-control">
                                    <option v-for="(day, index) in weekOfDay" :value="index + 1" :key="day">{{day}}</option>
                                </select>
                                <select v-model="period.period" class="form-control">
                                    <option :value="item" v-for="item in periodOptions" :key="item">{{item}}</option>
                                </select>
                            </li>
                            <button class="btn btn-outline-info btn-sm" @click="addPeriodForm">曜日・時限を追加</button>
                        </ul>
                    </div>
                </div>
                <div class="text-center">
                    <button class="btn btn-danger btn-sm" @click="deleteCourse">この時間割を消す</button>
                </div>
            </div>
        </div>
        <div class="course-content-body">
            <ul class="assignment-calendar">
                <template v-for="date in dateList">
                    <li v-if="findAssignment(date)" :key="date" class="assignment-calendar-item">
                        {{date}}
                        <assignment :assignment="findAssignment(date)" />
                    </li>
                    <li v-else :key="date" class="assignment-calendar-item" @click="addAssignment(date)">
                        {{date}}
                    </li>
                </template>
            </ul>
            <div class="assignment-modal" v-if="modalVisibility" @click="modalVisibility = false">
                <div class="assignment-modal-close"><i class="fas fa-times"></i></div>
                <div class="assignment-modal-body" @click.stop>
                    {{modalAssignment.date}}
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Assignment from './Assignments';

export default {
    components: {
        Assignment
    },
    data: function(){
        return{
            course: {},
            formData: {},
            courseType: ['対面', 'オンライン'],
            weekOfDay: ['月', '火', '水', '木', '金', '土'],
            periodOptions: [1, 2, 3, 4, 5, 6],
            setting: {},
            isInfoOpen: true,
            isEditing: false,
            isAlreadyRegistered: false,
            selected: {
                day: undefined,
                period: undefined
            },
            periodInfo: {},
            dateList: [],
            assignments: [],
            modalAssignment: {
                date: undefined,
                body: '',
                done_flg: false,
            },
            modalVisibility: false
        }
    },
    computed: {
        auth: function(){
            return __auth();
        },
        periodList: function(){
            return this.course.periods.map(p => {
                return p.day_of_week;
            });
        },
    },
    methods: {
        async getCourse(){
            const params = await this.splitParams(location.search.substring(1));
            if(!params.course){
                this.isEditing = true;
                this.$set(this.formData, 'periods', [
                    {
                        'day_of_week' : params.day,
                        'period': params.period,
                    }
                ]);
                this.$set(this.formData, 'type', '対面');
            }else{
                axios.get(`/api/course/detail/?course=${params.course}`)
                .then(({data}) => {
                    this.course = data[0];
                    this.formData = data[0];
                    this.isAlreadyRegistered = true;
                    this.periodInfo = this.findPeriod()[0];
                    this.getAssignments();
                    this.getDateList();
                });
            }
        },
        getAssignments: function(){
            axios.get(`/api/course/assignments?course=${this.course.id}`)
                .then(({data}) => {
                    data.forEach(da => {
                        this.assignments.push({
                            ...da,
                            'date': this.getDateOfAssignment(da.date),
                        });
                    })
                })
        },
        getDateOfAssignment: function(date){
            var item = new Date(date);
            return item.getMonth() + 1 + '/' + item.getDate();
        },
        findAssignment: function(date){
            return this.assignments.find(a => a.date === date);
        },
        getDateList: function(){
            var end = new Date(this.setting.end_date);
            var start = new Date(this.setting.start_date);
            var diff = (end.getTime() - start.getTime())/ (1000*60*60*24);
            var startDay = start.getDay();
            var endDay = end.getDay();
            var dateListArray = [];

            for(var i = startDay; i < diff + 7; i = i + 7){
                //console.log(i);
                for(var j = 0; j < this.course.periods.length; j++){
                    var num = this.course.periods[j].day_of_week + i - startDay * 2;
                    if(num > 0 && num <= diff){
                        var origin = new Date(this.setting.start_date);
                        var date = new Date(origin.setDate(origin.getDate() + num));
                        var returnDate = date.getMonth() + 1 + '/' + date.getDate();
                        dateListArray.push(returnDate);
                    }
                }
            }
            this.dateList = dateListArray;
        },
        splitParams: function(param){
            param = param.split('&');
            var obj = {};
            for(var i = 0; i < param.length; i++){
                var item = param[i].split('=');
                obj[item[0]] = item[1];
            }
            this.selected.day = Number(obj.day);
            this.selected.period = Number(obj.period);
            return obj;
        },
        getWeekOfDay: function(num){
            return this.weekOfDay[num - 1];
        },
        getPeriodTime: function(period){
            return this.setting.periods[period];
        },
        toggleInfo: function(){
            this.isInfoOpen = !this.isInfoOpen;
        },
        edit: function(){
            this.isEditing = !this.isEditing;
        },
        addPeriodForm: function(){
            this.formData.periods.push({
                'day_of_week': '',
                'period': ''
            });
        },
        save: function(){
            axios.post(`/course/update/${this.course.id}`, {
                'name': this.formData.name,
                'teacher': this.formData.teacher,
                'type': this.formData.type,
                'periods': this.formData.periods
            }).then(({data}) => {
                console.log(data);
                this.isEditing = false;
            });
        },
        findPeriod: function(){
            return this.course.periods.filter(p => p.day_of_week === this.selected.day && p.period === this.selected.period);
        },
        deleteCourse: function(){
            axios.delete(`/period/${this.periodInfo.id}`)
                .then(() => {
                    this.$router.push('/timetable');
                })
        },
        addAssignment: function(date){
            this.modalVisibility = true;
            this.modalAssignment.date = date;
        }
    },
    async mounted(){
        await axios.get(`/api/user/setting/${this.auth.id}`).then(({data}) => {
            this.setting = data;
        })
        this.getCourse();
    }
}
</script>