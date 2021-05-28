<template>
    <div>123</div>
</template>

<script>
    export default {
        data() {
            return {
                salesOrder : [],
                res:[],
                payment_res:[]
            }
        },

        props: [
            'so',
        ],

        mounted() {
            $('#myModal').on('hidden.bs.modal', () => {location.reload()})
            $('#paymentModal').on('hidden.bs.modal', () => {location.reload()})
        },

        created() {
            this.salesOrder = JSON.parse(this.so);
        },

        methods: {

        }
    }
</script>
