<template>
    <div class="setting-semester-card" @click="selectSemester">
        <button class="semester-option-button" @click.stop="showOption">
          <i class="fas fa-bars"></i>
        </button>
        <p class="setting-semester-card-head">{{semester.year}}年度 {{semester.type}}学期</p>
        <div class="d-flex setting-semester-card-date">
        <img src="/images/course-period.png" alt="学期の期間" class="setting-semester-card-icon">
        <p class="mypage-info setting-semester-card-text">{{semester.start_date}} - {{semester.end_date}}</p>
        </div>
    </div>
</template>

<script>
export default {
    props: ['semester'],
    methods: {
        selectSemester(){
            this.$emit('select', this.semester);
        },
        showOption(){
            this.$emit('show', this.semester);
        }
    }
}
</script>