<template>
    <div>
        <div class="setting-body">
            <setting-head
                name="授業カラー"
                link="setting"
            />
            <template v-for="(courseList, index) in arrangedCourses">
                <template v-if="courseList.length > 0">
                    <h3 class="setting-subtitle" :key="dayOfWeek[index]">{{dayOfWeek[index]}}</h3>
                    <ul class="setting-list-container" :key="index">
                        <course-color-item v-for="course in courseList" :item="course" :key="course.course.id" @open="toggleColorModal" class="setting-list"/>
                    </ul>
                </template>
            </template>
        </div>
        <div class="modal-base" v-show="colorModalVisibility" @click="toggleColorModal({color: '', id: ''})">
            <div class="modal-base-inner" @click.stop>
                <p class="setting-list-name">授業カラーを選択</p>
                <color-lists v-model="currentColor" />
                <div class="setting-modal-confirm">
                    <button class="btn btn-sm btn-danger" @click.stop="toggleColorModal({color: '', id: ''})">取消</button>
                    <button class="btn btn-sm btn-success" @click.stop="changeColor">変更</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import SettingHead from './modules/Setting-head'
import ColorLists from './modules/Color-lists'
import CourseColorItem from './modules/Course-color-item'

export default {
    components: {
        ColorLists,
        CourseColorItem,
        SettingHead,
    },
    data(){
        return{
            arrangedCourses: [],
            courses: {},
            colorModalVisibility: false,
            currentColor: '',
            currentId: '',
            dayOfWeek: ['月曜日', '火曜日', '水曜日', '木曜日', '金曜日', '土曜日'],
            setting: {}
        }
    },
    methods: {
        backToTimetable(){
            this.$router.push('/setting');
        },
        changeColor(){
            axios.put(`/course/color/${this.currentId}`, {
                color: this.currentColor
            })
            .then(({data}) => {
                this.courses = this.courses.map(course => {
                    if(course.course_id === data.id){
                        var item = {
                            ...course,
                            course: data
                        }
                        console.log(item);
                        return item
                    }else{
                        return course
                    }
                })
                this.splitCourses();
                this.colorModalVisibility = false;
            })
            .catch((err) => {
                console.log(err);
            })
        },
        getCourses(semesterId){
          axios.get(`/api/period/${this.auth.id}?year=${this.setting.semester.year}&type=${this.setting.semester.type}`)
            .then(({data}) => {
              data.sort(this.sortCourses)
              this.courses = data;
              this.splitCourses();
            })
        },
        getSetting(){
          axios.get(`/api/user/setting/${this.auth.id}`)
            .then(({data}) => {
              this.setting = data;
              this.getCourses(data.semester.id);
            });
        },
        toggleColorModal(arg){
            this.colorModalVisibility = !this.colorModalVisibility;
            this.currentColor = arg.color;
            this.currentId = arg.id;
        },
        sortCourses(a, b){
            return a.day_of_week - b.day_of_week;
        },
        splitCourses(){
            this.arrangedCourses = [];
            this.dayOfWeek.forEach((d, index) => {
                var item = this.courses.filter(c => c.day_of_week === (index + 1));
                this.arrangedCourses.push(item);
            })
        }
    },
    computed: {
        auth(){
            return __auth();
        },
        color: {
            get(){
                if(this.currentItem.course){
                    return this.currentItem.course.color;
                }else{
                    return '';
                }
            },
            set(value){

            }
        }
    },
    mounted(){
        this.getSetting();
    }
}
</script>