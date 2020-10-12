<template>
  <div class="setting-body">
    <setting-head
      name="年度/学期切替"
      link="setting/mypage"
      :option-button-visibility=false
    />
    <div class="semester-content-body">
      <div class="semester-surface-body" :data-visibility="!listVisibility ? 'visible' : 'hidden'">
        <p class="setting-text">時間割を表示させたい年度と学期を選択してください。<br>学期開始日と終了日を正しく入力しないとデータが正しく表示されないことがあります。</p>
        <p class="setting-subtitle">選択中</p>
        <semester-card 
          :semester="semesters.current" 
          @show="showOption"
        />
        <div class="semester-select-button" role="button" @click="toggleListVisibility">学期一覧から選択する</div>
      </div>
      <div class="semester-list-body" :data-visibility="listVisibility ? 'visible' : 'hidden'">
        <semester-card
          v-for="semester in semesters.semesters" 
          :semester="semester" 
          :key="semester.id"
          :data-selected="semester.id === current.id ? 'selected' : 'non-selected'"
          @select="selectSemester"
          @show="showOption"
        />
      </div>
      <div class="setting-semester-button-block" v-if="listVisibility">
        <button class="setting-semester-button setting-semester-delete" @click="cancelChange">変更を取り消す</button>
        <button class="setting-semester-button setting-semester-save" @click="save">変更を保存する</button>
      </div>
    </div>
    <loading v-show="loadingVisibility" />
    <transition name="fade">
      <semester-option
        v-show="optionVisibility" 
        :visibility="optionVisibility" 
        @close="optionVisibility = false"
        @delete="showDeleteModal"
        @edit="editSemester"
      />
    </transition>
    <delete-modal 
      v-show="deleteModalVisibility" 
      :info="optionTargetSemester" 
      type="semester" 
      @close="deleteModalVisibility = false"
      @delete="deleteSemester"
    />
    <semester-modal
      :semester="optionTargetSemester"
      v-show="editModalVisibility"
      @cancel="editModalVisibility = false"
    />
  </div>
</template>

<script>
import DeleteModal from './modules/Delete-modal'
import Loading from './modules/Loading'
import SettingHead from './modules/Setting-head'
import SemesterCard from './modules/SemesterCard'
import SemesterModal from './modules/SemesterModal'
import SemesterOption from './modules/SemesterOption'

export default {
  components: {
    DeleteModal,
    Loading,
    SettingHead,
    SemesterCard,
    SemesterModal,
    SemesterOption
  },
  data(){
    return {
      current: {},
      deleteModalVisibility: false,
      editModalVisibility: false,
      endYear: 2050,
      listVisibility: false,
      loadingVisibility: false,
      optionVisibility: false,
      semesters: {},
      startYear: 2010,
      optionTargetSemester: {}
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
    changeCurrent(semester){
      this.current = semester;
    },
    cancelChange(){
      this.listVisibility = false;
    },
    deleteSemester(){
      const deleteItemId = this.semesters.current.id;
      axios.delete(`/setting/mypage/semester/delete/${this.semesters.current.id}`)
      .then(({data}) => {
        this.deleteModalVisibility = false;
        this.semesters.semesters = this.semesters.semesters.filter(s => s.id !== deleteItemId);
        this.save(this.semesters.semesters[0]);
      })
      .catch(err => {
        console.log(err);
      })
    },
    editSemester(){
      this.editModalVisibility = true;
      this.optionVisibility = false;
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
        this.current = {
          ...data.current,
          startDate: {...startDate},
          endDate: {...endDate}
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
      var formItem = this.current;
      var startDate = this.getDateObj(formItem.start_date);
      var endDate = this.getDateObj(formItem.end_date);
      axios.post(`/setting/mypage/semester/create/${this.auth.id}`, {
        'year': formItem.year,
        'type': formItem.type,
        'start_date': this.formatDate(startDate.year, startDate.month, startDate.day),
        'end_date': this.formatDate(endDate.year, endDate.month, endDate.day)
      })
      .then(({data}) => {
        this.loadingVisibility = false;
        this.listVisibility = false;
      })
      .catch(err => {
        console.log(err);
      })
    },
    selectSemester(semester){
      let current = this.semesters.current;
      this.changeCurrent(semester);
    },
    showDeleteModal(){
      if(this.optionTargetSemester.id === this.current.id){
        alert('選択中の学期は削除できません');
        return
      }
      this.deleteModalVisibility = true;
      this.optionVisibility = false;
    },
    showOption(semester){
      this.optionTargetSemester = semester;
      this.optionVisibility = true;
    },
    toggleListVisibility(){
      this.listVisibility = !this.listVisibility;
    }
  },
  mounted(){
    this.getSemesters();
  }
}
</script>