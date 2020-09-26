<template>
  <div class="setting-body">
    <setting-head
      name="年度/学期切替"
      link="setting/mypage"
      option-button-visibility=false
    />
    <form class="setting-semester-form">
      <div class="setting-semester-form-block">
        <p class="setting-semester-form-text">年度を選択</p>
        <select class="form-control" v-model="semesters.current.year" @change="changeCurrent" ref="year">
          <option :value="year" v-for="year in years" :key="year">{{year}}</option>
        </select>
      </div>
      <div class="setting-semester-form-block">
        <p class="setting-semester-form-text">学期を選択</p>
        <select class="form-control" v-model="semesters.current.type" @change="changeCurrent" ref="semester">
          <option :value="item" v-for="(item, index) in semesters.semesterEnum" :key="index">{{item}}</option>
        </select>
      </div>
      <div class="setting-semester-form-block">
        <p class="setting-semester-form-text">学期開始日</p>
        <div class="d-flex">
          <input class="form-control" type="text" v-model="semesters.current.startDate.year">
          <input class="form-control" type="text" v-model="semesters.current.startDate.month">
          <input class="form-control" type="text" v-model="semesters.current.startDate.day">
        </div>
      </div>
      <div class="setting-semester-form-block">
        <p class="setting-semester-form-text">学期終了日</p>
        <div class="d-flex">
          <input class="form-control" type="text" v-model="semesters.current.endDate.year">
          <input class="form-control" type="text" v-model="semesters.current.endDate.month">
          <input class="form-control" type="text" v-model="semesters.current.endDate.day">
        </div>
      </div>
    </form>
    <button class="setting-semester-save" @click="save">変更を保存する</button>
    <loading v-show="loadingVisibility" />
  </div>
</template>

<script>
import Loading from './modules/Loading'
import SettingHead from './modules/Setting-head'

export default {
  components: {
    Loading,
    SettingHead
  },
  data(){
    return {
      semesters: {},
      startYear: 2010,
      endYear: 2050,
      loadingVisibility: false
    }
  },
  computed: {
    auth(){
      return __auth();
    },
    years(){
      var yearNum = this.endYear - this.startYear;
      var yearsArray = [];
      for(var i = 0; i < yearNum; i++){
        var item = this.startYear + i;
        yearsArray.push(this.startYear + i);
      }
      return yearsArray;
    },
  },
  methods: {
    changeCurrent(){
      var year = this.$refs.year.value;
      var semester = this.$refs.semester.value;
      var target = this.semesters.semesters.filter(s => {
        if(s.year == year && s.type == semester){
          return s;
        }
      })
      if(target.length > 0){
        var startDate = this.getDateObj(target[0].start_date);
        var endDate = this.getDateObj(target[0].end_date);
        this.semesters.current = {
          ...target[0],
          startDate: {...startDate},
          endDate: {...endDate}
        }
      }
    },
    getSemesters(){
      axios.get(`/api/setting/semester/detail/${this.auth.id}`)
      .then(({data}) => {
        var startDate = this.getDateObj(data.current.start_date);
        var endDate = this.getDateObj(data.current.end_date);
        this.semesters = {
          ...data,
          current: {
            ...data.current,
            startDate: {...startDate},
            endDate: {...endDate}
          }
        }
      });
    },
    getDate(dateArg){
      return new Date(dateArg);
    },
    getYear(dateArg){
      var date = this.getDate(dateArg);
      var year = date.getFullYear();
      return year;
    },
    getMonth(dateArg){
      var date = this.getDate(dateArg);
      var month = date.getMonth() + 1;
      return month;
    },
    getDay(dateArg){
      var date = this.getDate(dateArg);
      var day = date.getDate();
      return day;
    },
    getDateObj(dateArg){
      return {
        'year': this.getYear(dateArg),
        'month': this.getMonth(dateArg),
        'day': this.getDay(dateArg)
      }
    },
    formatDate(year, month, day){
      return year + '-' + month + '-' + day;
    },
    save(){
      this.loadingVisibility = true;
      var formItem = this.semesters.current;
      axios.post(`/setting/mypage/semester/create/${this.auth.id}`, {
        'year': formItem.year,
        'type': formItem.type,
        'start_date': this.formatDate(formItem.startDate.year, formItem.startDate.month, formItem.startDate.day),
        'end_date': this.formatDate(formItem.endDate.year, formItem.endDate.month, formItem.endDate.day)
      })
      .then(({data}) => {
        this.loadingVisibility = false;
      })
      .catch(err => {
        console.log(err);
      })
    }
  },
  mounted(){
    this.getSemesters();
  }
}
</script>