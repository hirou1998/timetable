<template>
	<div class="course-detail">
		<my-header :name="course.name" :auth="auth" :bk-color="course.color" />
		<div class="course-detail-body">
			<h2 class="course-detail-body-title" @click="toggleInfo" v-if="!isEditing">
				{{course.name}}
				<template>
					<i class="fas fa-angle-down" v-if="!isInfoOpen" ></i>
					<i class="fas fa-angle-up" v-else></i>
				</template>
				<span class="course-setting-button" @click.stop="toggleDetailOptionWindow">
					<i class="fas fa-ellipsis-h"></i>
				</span>
			</h2>
			<div class="form-group" v-else>
				<input type="text" class="form-control" ref="courseName" :value="formData.name" placeholder="科目名">
			</div>
			<div class="course-detail-body-info" v-if="isInfoOpen">
				<button class="btn btn-info btn-sm course-detail-body-info-edit" @click="save" v-if="isEditing && isAlreadyRegistered">保存</button>
				<button class="btn btn-danger btn-sm course-detail-body-info-cancel" @click="edit" v-if="isEditing && isAlreadyRegistered">取消</button>
				<button class="btn btn-success btn-sm course-detail-body-info-edit" @click="register" v-if="isEditing && !isAlreadyRegistered">登録</button>
                <button class="btn btn-danger btn-sm course-detail-body-info-cancel" @click="backToTimetable" v-if="isEditing && !isAlreadyRegistered">取消</button>
				<div class="course-detail-body-info-item">
					<img src="/images/teacher-icon.png" alt="">
					<p class="course-detail-body-text" v-if="!isEditing">{{course.teacher}} 先生</p>
					<div class="form-group" v-else>
						<input type="text" class="form-control" ref="teacherName" :value="formData.teacher" placeholder="先生の名前">
					</div>
				</div>
				<div class="course-detail-body-info-item">
					<img src="/images/period-icon.png" alt="">
					<div>
						<p class="course-detail-body-text" v-if="!isEditing">{{course.type}}</p>
						<div class="form-group" v-else>
							<select ref="courseType" :value="formData.type" class="form-control" data-live-search="true">
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
							<li class="form-group course-detail-body-info-select-flex" v-for="(period, index) in formData.periods" :key="period.day_of_week + '-' + period.period">
								<select :ref="'dayOfWeek' + index" :value="period.day_of_week" class="form-control">
									<option v-for="(day, index) in weekOfDay" :value="index + 1" :key="day">{{day}}</option>
								</select>
								<select :ref="'period' + index" :value="period.period" class="form-control">
									<option :value="item" v-for="item in periodOptions" :key="item">{{item}}</option>
								</select>
							</li>
							<button class="btn btn-outline-info btn-sm" @click="addPeriodForm">曜日・時限を追加</button>
						</ul>
					</div>
				</div>
			</div>
		</div>
		<div class="course-content-body">
				<ul class="assignment-calendar" ref="assignments" id="assignment">
						<assignment-block
								v-for="(date, index) in dateList"
								:class="isNextDay(index) ? 'next' : ''"
								:id="isNextDay(index) ? 'next' : ''"
								:key="date + index + Math.random()"
								:date="date"
								:assignment="findAssignment(date)" 
								:is-next-day="isNextDay(index)"
								@add="addAssignment"
								ref="items" />
				</ul>
				<assignment-modal ref="modal" v-show="modalVisibility" @close="modalVisibility = false" :assignment="modalAssignment" @update="updateAssignment" @filter="filterItem" @add="addAssignmentByModal" />
		</div>
		<delete-modal
				v-show="deleteModalVisibility"
				:info="course"
				type="course"
				@close="deleteModalVisibility = false"
				@delete="deleteCourse"
		/>
        <transition name="fade">
            <detail-option-window
                v-show="detailOptionWindowVisibility"
                @close="toggleDetailOptionWindow"
                @delete="toggleDeleteModal"
                @edit="edit"
                :visibility="detailOptionWindowVisibility"
            />
        </transition>
	</div>
</template>

<script>
import AssignmentBlock from './Assignment-block';
import AssignmentModal from './Assignment-modal';
import DeleteModal from './modules/Delete-modal';
import DetailOptionWindow from './modules/Detail-option-window';
import MyHeader from './modules/Header';

export default {
    components: {
        AssignmentBlock,
        AssignmentModal,
        DeleteModal,
        DetailOptionWindow,
        MyHeader
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
                memo: '',
                id: undefined
            },
            modalVisibility: false,
            deleteModalVisibility: false,
            detailOptionWindowVisibility: false
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
        today: function(){
            return new Date();
        },
        thisYear: function(){
            return new Date().getFullYear();
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
                    this.formData = this.course;
                    this.isAlreadyRegistered = true;
                    this.periodInfo = this.findPeriod()[0];
                    this.getAssignments();
                    this.getDateList();
                });
            }
        },
        getAssignments: function(){
            axios.get(`/api/course/assignments/c/${this.course.id}`)
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
        isNextDay: function(index){
            return this.indexOfNextDay() === index ? true : false;
        },
        indexOfNextDay: function(){
            for(var i = 0; i < this.dateList.length; i++){
                var date = new Date(this.dateList[i]);
                date.setFullYear(this.thisYear);
                date.setHours(23);
                date.setMinutes(59);
                var prev = new Date(this.dateList[i - 1]);
                prev.setFullYear(this.thisYear);
                prev.setHours(23);
                prev.setMinutes(59);
                if(this.today > prev && this.today <= date){
                    return i;
                    break;
                }
            }
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
        toggleDetailOptionWindow(){
            this.detailOptionWindowVisibility = !this.detailOptionWindowVisibility;
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
        getFormData(){
            this.formData.teacher = this.$refs['teacherName'].value;
            this.formData.type = this.$refs['courseType'].value;
            this.formData.name = this.$refs['courseName'].value;
            this.formData.periods = this.formData.periods.map((period, index) => {
                var dKey = 'dayOfWeek' + index;
                var pKey = 'period' + index;
                return {...period, day_of_week: Array.from(this.$refs[dKey])[0].value, period: Array.from(this.$refs[pKey])[0].value}
            });
        },
        save: function(){
            this.getFormData();
            axios.post(`/course/update/${this.course.id}`, {
                'name': this.formData.name,
                'teacher': this.formData.teacher,
                'type': this.formData.type,
                'periods': this.formData.periods
            }).then(({data}) => {
                this.isEditing = false;
            });
        },
        register: function(){
            this.getFormData();
            axios.post(`/course/register/${this.auth.id}`, {
                'name': this.formData.name,
                'teacher': this.formData.teacher,
                'type': this.formData.type,
                'periods': this.formData.periods
            }).then(({data}) => {
                this.backToTimetable();
            }).catch((err) => {
                console.log(err);
            })
        },
        findPeriod: function(){
            return this.course.periods.filter(p => p.day_of_week === this.selected.day && p.period === this.selected.period);
        },
        toggleDeleteModal(){
            this.deleteModalVisibility = !this.deleteModalVisibility
        },
        backToTimetable(){
            this.$router.push('/timetable');
        },
        deleteCourse: function(){
            axios.delete(`/period/${this.periodInfo.id}`)
                .then(() => {
                    this.backToTimetable();
                })
        },
        addAssignment: function(date){
            this.modalVisibility = true;
            this.modalAssignment.date = date;
            var item = this.findAssignment(date);
            if(item){
                this.modalAssignment.body = item.body;
                this.modalAssignment.done_flg = item.done_flg;
                this.modalAssignment.memo = item.memo;
                this.modalAssignment.id = item.id
            }else{
                this.modalAssignment.body = '';
                this.modalAssignment.done_flg = false;
                this.modalAssignment.memo = '';
                this.modalAssignment.id = undefined;
                this.$refs.modal.isEditing = true;
                this.$refs.modal.isAdding = true;
            }
        },
        updateAssignment: function(data){
            this.assignments = this.assignments.map(a => {
                if(a.id === data.id){
                    return a = {
                        ...data,
                        'date': this.getDateOfAssignment(data.date),
                    }
                }else{
                    return a;
                }
            })
        },
        filterItem: function(id){
            this.assignments = this.assignments.filter(a => a.id !== id);
        },
        addAssignmentByModal: function(item){
            this.assignments.push({
                ...item,
                date: this.getDateOfAssignment(item.date)
            });
        },
    },
    async mounted(){
        await axios.get(`/api/user/setting/${this.auth.id}`).then(({data}) => {
            this.setting = data;
        })
        await this.getCourse();
    }
}
</script>