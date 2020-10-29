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
    <div class="setting-semester-button-block">
      <button class="setting-semester-button setting-semester-delete" @click="showDeleteModal">この学期を削除する</button>
      <button class="setting-semester-button setting-semester-save" @click="save">変更を保存する</button>
    </div>
    <loading v-show="loadingVisibility" />
    <delete-modal 
      v-show="deleteModalVisibility" 
      :info="semesters.current" 
      type="semester" 
      @close="deleteModalVisibility = false"
      @delete="deleteSemester"
    />
  </div>