<template>
    <div>
        <div class="setting-head">
            <button class="setting-head-back" @click="backToTimetable">
                <i class="fas fa-chevron-left"></i>
            </button>
            <h2 class="setting-head-title">時間割設定</h2>
        </div>
        <div class="setting-body">
            <h3>カスタマイズ</h3>
            <ul></ul>
        </div>
    </div>
</template>

<script>
export default {
    methods: {
        backToTimetable: function(){
            this.$router.push('/timetable');
        }
    }
}
</script>