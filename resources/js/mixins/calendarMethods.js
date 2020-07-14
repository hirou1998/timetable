var calendarMethods = {
    methods: {
        movePrev: function(){
            if(this.currentMonth === 1){
                this.currentMonth = 12;
                this.currentYear = this.currentYear - 1;
            }else{
                this.currentMonth = this.currentMonth - 1;
            }
        },
        moveNext: function(){
            if(this.currentMonth === 12){
                this.currentMonth = 1;
                this.currentYear = this.currentYear + 1;
            }else{
                this.currentMonth = this.currentMonth + 1;
            }
        },
    },
    computed: {
        firstDay: function(){
            return new Date(this.currentYear, this.currentMonth - 1, 1).getDay();
        },
        lastDay: function(){
            return new Date(this.currentYear, this.currentMonth, 0).getDate();
        },
        space: function(){
            return [...Array(this.firstDay)].map(i => " ");
        },
        days: function(){
            var dayList = [...Array(this.lastDay)].map((d, i) => i + 1);
            return this.space.concat(dayList);
        }
    },
    mounted(){
        var date = new Date();
        this.todayYear = date.getFullYear();
        this.todayMonth = date.getMonth() + 1;
        this.todayDate = date.getDate();

        this.currentYear = date.getFullYear();
        this.currentMonth = date.getMonth() + 1;
        this.currentDate = date.getDate();
        this.currentDay = date.getDay();
    }
}