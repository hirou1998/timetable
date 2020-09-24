<template>
  <div class="setting-body">
    <setting-head
      name="年度/学期切替"
      link="setting/mypage"
      option-button-visibility=false
    />
    <form class="setting-semester-form">
      <div class="setting-semester-form-block">
        <p class="setting-semester-form-text">学期を選択</p>
        <select class="form-control" v-model="semesters.current.type">
          <option :value="item" v-for="(item, index) in semesters.semesterEnum" :key="index">{{item}}</option>
        </select>
      </div>
      <div class="setting-semester-form-block">
        <p class="setting-semester-form-text">年度を選択</p>
        <select class="form-control" v-model="semesters.current.year">
          <option :value="year" v-for="year in years" :key="year">{{year}}</option>
        </select>
      </div>
    </form>
    <button class="setting-semester-save">変更を保存する</button>
  </div>
</template>

<script>
import SettingHead from './modules/Setting-head'

export default {
  components: {
    SettingHead
  },
  data(){
    return {
      semesters: {},
      startYear: 2010,
      endYear: 2050
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
    }
  },
  methods: {
    // getCurrentSemester(){
    //   axios.get(`/api/setting/semester`)
    // },
    getSemesters(){
      axios.get(`/api/setting/semester/detail/${this.auth.id}`)
      .then(({data}) => {
        this.semesters = data;
      });
    },
    getSemesterEnum(){
      axios.get(`/api/setting/semester/enum`)
      .then(({data}) => {
        this.semesterEnum = data;
      });
    }
  },
  mounted(){
    this.getSemesters();
    //this.getSemesterEnum();
  }
}
</script>