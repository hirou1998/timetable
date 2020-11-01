<template>
  <div class="setting-body">
    <setting-head
      name="年度/学期切替"
      link="setting/mypage"
      :option-button-visibility="optionButtonVisibility"
    />
    <div class="semester-content-body">
      <div class="semester-surface-body" :data-visibility="!listVisibility ? 'visible' : 'hidden'">
        <p class="setting-text">時間割を表示させたい年度と学期を選択してください。<br>学期開始日と終了日を正しく入力しないとデータが正しく表示されないことがあります。</p>
        <p class="setting-subtitle">選択中</p>
        <semester-card 
          :semester="current" 
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
        <div class="semester-add-block">
          <button class="semester-add-button btn" @click="addSemester">
            <i class="fas fa-plus"></i>
          </button>
        </div>
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
      v-model="optionTargetSemester"
      v-show="editModalVisibility"
      @cancel="editModalVisibility = false"
      @edit="edit()"
      @add="add()"
      :types="semesters.semesterType"
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
      defaultSemester: {
        endDate: {
          year: '',
          month: '',
          day: ''
        },
        startDate: {
          year: '',
          month: '',
          day: ''
        },
        type: '',
        year: '',
        user_id: ''
      },
      editModalVisibility: false,
      endYear: 2050,
      listVisibility: false,
      loadingVisibility: false,
      optionVisibility: false,
      semesters: {},
      startYear: 2010,
      optionTargetSemester: {
        endDate: {
          year: '',
          month: '',
          day: ''
        },
        startDate: {
          year: '',
          month: '',
          day: ''
        },
        type: '',
        year: '',
        user_id: ''
      },
      optionButtonVisibility: false,
    }
  },
  computed: {
    auth(){
      return __auth();
    },
  },
  methods: {
    add(){
      this.formatOptionTargetSemester();
      let sameSemester = this.semesters.semesters.filter(s => {
        return s.year == this.optionTargetSemester.year && s.type == this.optionTargetSemester.type;
      });
      if(sameSemester){
        alert('すでに登録されている学期は追加できません。');
        return
      }
      axios.post(`/setting/mypage/semester/create/${this.auth.id}`, {
        'year': this.optionTargetSemester.year,
        'type': this.optionTargetSemester.type,
        'start_date': this.optionTargetSemester.start_date,
        'end_date': this.optionTargetSemester.end_date
      })
      .then(({data}) => {
        if(data.id){
          this.semesters.semesters.push(data);
        }
        this.editModalVisibility = false;
      })
      .catch((err, msg) => {
        this.editModalVisibility = false;
        if(err.response.data.error){
          alert(err.response.data.error)
        }else{
          alert('予期せぬエラーが発生しました。')
        }
      })
    },
    addSemester(){
      this.optionTargetSemester = this.defaultSemester;
      this.editSemester();
    },
    changeCurrent(semester){
      this.current = semester;
    },
    cancelChange(){
      this.listVisibility = false;
      this.current = this.semesters.current;
    },
    deleteSemester(){
      const deleteItemId = this.semesters.current.id;
      axios.delete(`/setting/mypage/semester/delete/${this.semesters.current.id}`)
      .then(({data}) => {
        this.deleteModalVisibility = false;
        if(data.id){
          this.semesters.semesters = this.semesters.semesters.filter(s => s.id !== deleteItemId);
        }
      })
      .catch(err => {
        console.log(err);
      })
    },
    edit(){
      this.formatOptionTargetSemester();
      axios.put(`/setting/mypage/semester/edit/${this.optionTargetSemester.id}`, {
        'year': this.optionTargetSemester.year,
        'type': this.optionTargetSemester.type,
        'start_date': this.optionTargetSemester.start_date,
        'end_date': this.optionTargetSemester.end_date,
      })
      .then(() => {
        let newSemesters = this.semesters.semesters.map(s => {
          if(s.id == this.optionTargetSemester.id){
            return this.optionTargetSemester
          }else{
            return s;
          }
        })
        this.semesters.semesters = newSemesters;
        if(this.current.id == this.optionTargetSemester.id){
          this.current = this.optionTargetSemester
        }
        this.editModalVisibility = false;
      })
      .catch(err => {
        console.log(err);
      })
    },
    editSemester(){
      this.editModalVisibility = true;
      this.optionVisibility = false;
    },
    formatOptionTargetSemester(){
      let editedStartDate = this.optionTargetSemester.startDate;
      let editedEndDate = this.optionTargetSemester.endDate;
      this.optionTargetSemester.start_date = this.formatDate(editedStartDate.year, editedStartDate.month, editedStartDate.day);
      this.optionTargetSemester.end_date = this.formatDate(editedEndDate.year, editedEndDate.month, editedEndDate.day);
    },
    getSemesters(){
      axios.get(`/api/setting/semester/detail/${this.auth.id}`)
      .then(({data}) => {
        var startDate = this.getDateObj(data.current.start_date);
        var endDate = this.getDateObj(data.current.end_date);
        var shapedSemesters = data.semesters.map(s => {
          var startDate = this.getDateObj(s.start_date);
          var endDate = this.getDateObj(s.end_date);
          return {
            ...s,
            startDate: {...startDate},
            endDate: {...endDate}
          }
        });
        this.semesters = {
          ...data,
          current: {
            ...data.current,
            startDate: {...startDate},
            endDate: {...endDate}
          },
          semesters: shapedSemesters
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
      axios.post(`/setting/mypage/semester/change/${this.current.id}`)
      .then(({data}) => {
        this.loadingVisibility = false;
        this.listVisibility = false;
      })
      .catch(err => {
        console.log(err)
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