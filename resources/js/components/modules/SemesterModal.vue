<template>
    <div class="modal-base">
        <section class="semester-edit-window">
            <div class="semester-edit-inner">
                {{year}}
            </div>
            <div class="d-flex justify-content-space-around">
                <button class="btn btn-sm btn-danger" @click="cancelEdit">キャンセル</button>
                <button class="btn btn-sm btn-success">変更を保存</button>
            </div>
        </section>
    </div>
</template>

<script>
export default {
    model: {
        prop: 'semester',
        event: 'change'
    },
    props: ['semester'],
    computed: {
        year: {
            get(){
                return this.semester.year;
            },
            set(year){
                this.updateValue({year});
            }
        },
        type: {
            get(){
                return this.semester.type;
            },
            set(type){
                this.updateValue({type});
            }
        },
        startYear: {
            get(){
                return this.semester.startDate.year;
            },
            set(year){
                let startDate = this.updateStartDate({year});
                this.updateValue({startDate})
            }
        }
    },
    methods: {
        cancelEdit(){
            this.$emit('cancel');
        },
        updateValue(value){
            this.$emit('change', {...this.semester, ...value})
        },
        updateStartDate(value){
            return {
                ...this.semester.startDate,
                ...value
            }
        },
        updateEndDate(value){
            return {
                ...this.semester.endDate,
                ...value
            }
        }
    }
}
</script>