<template>
    <div>
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title"></h3>

                <div class="box-tools">
                    <div class="btn-group pull-right" style="margin-right: 5px">
                        <a :href="'/admin/projects/'+this.salesOrder.project_id" class="btn btn-sm btn-default" title="List">
                            <i class="fa fa-arrow-left"></i><span class="hidden-xs"> 返回</span>
                        </a>
                    </div>

                    <div class="btn-group pull-right" style="margin-right: 5px">
                        <a :href="'/admin/sales-orders/'+this.salesOrder.id+'/edit'" class="btn btn-sm btn-primary" title="编辑">
                            <i class="fa fa-edit"></i><span class="hidden-xs"> 编辑</span>
                        </a>
                    </div>

                </div>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-3">
                            <div class="info-box bg-green-gradient">
                                <span class="info-box-icon"><i class="fa fa-dollar"></i></span>

                                <div class="info-box-content">
                                    <span class="info-box-text" v-if="this.salesOrder.not_shipped < 0">发货总金额超出销售总金额</span>
                                    <span class="info-box-text" v-else>待发货总金额</span>
                                    <span class="info-box-number">{{ this.salesOrder.not_shipped }}</span>
                                    <div class="progress">
                                        <div class="progress-bar" :style="'width:' +this.salesOrder.shipped_progress+'%'"></div>
                                    </div>
                                    <span v-if="this.salesOrder.not_shipped == 0" class="progress-description">耗时 {{ this.salesOrder.days }} 天完成发货</span>
                                    <span v-else class="progress-description">{{ this.salesOrder.days }} 天增加 {{ this.salesOrder.shipped_progress }}%</span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="info-box bg-yellow-gradient">
                                <span class="info-box-icon"><i class="fa fa-dollar"></i></span>

                                <div class="info-box-content">
                                    <span class="info-box-text" v-if="this.salesOrder.not_received < 0">收款总金额超出销售总金额</span>
                                    <span class="info-box-text" v-else-if="this.salesOrder.not_received > 0">待收款总金额</span>
                                    <span class="info-box-text" v-else>收款完成</span>
                                    <span class="info-box-number">{{ this.salesOrder.not_received }}</span>
                                    <div class="progress">
                                        <div class="progress-bar" :style="'width:' +this.salesOrder.received_progress+'%'"></div>
                                    </div>
                                    <span v-if="this.salesOrder.not_received == 0" class="progress-description">耗时 {{ this.salesOrder.days }} 天完成收款</span>
                                    <span v-else class="progress-description">{{ this.salesOrder.days }} 天增加 {{ this.salesOrder.received_progress }}%</span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                        </div>

                    </div>
                </div>

                <div class="col-md-12">
                    <p>销售订单编号：{{ this.salesOrder.no }}</p>
                    <p>销售总金额：{{ this.salesOrder.amount }}</p>
                </div>
            </div>
        </div>

        <div class="box">
            <div class="box-header">
                <h3 class="box-title">发货序列</h3>
                <div class="box-tools">
                    <a :href="'/admin/sales-order-batches/create?so_id='+this.salesOrder.id" class="btn btn-xs btn-success"><i class="fa fa-plus"></i></a>
                </div>
            </div>

            <!-- /.box-header -->
            <div class="box-body no-padding">
                <table class="table table-striped">
                    <tbody>
                    <tr>
                        <th style="width: 60px">#</th>
                        <th>DO 编号</th>
                        <th>发货时间</th>
                        <th>批次金额</th>
                        <th>报关单号</th>
                        <th>盖章的报关单</th>
                        <th>备注</th>
                        <th>操作</th>
                    </tr>

                    <tr v-for="(item,key) in salesOrder.sales_order_batches">
                        <td>{{ ++key }}</td>
                        <td>{{ item.no }}</td>
                        <td>{{ item.delivery_at }}</td>
                        <td>$ {{ item.amount }}</td>
                        <td>{{ item.declaration_number }}</td>
                        <td><a v-if="item.file" :href="'/uploads/'+item.file" target="_blank"><i class="fa fa-download"></i></a></td>
                        <td>{{ item.comment }}</td>
                        <td>
                            <a :href="'/admin/sales-order-batches/'+item.id+'/edit'" class="btn btn-xs btn-default" ><i class="fa fa-edit"></i></a>
<!--                            <button class="btn btn-xs btn-default" @click="invoice(item.id)"><i class="fa fa-ticket"></i></button>-->
                        </td>
                    </tr>
                    <tr>
                        <td>合计：</td>
                        <td></td>
                        <td></td>
                        <td>$ {{ salesOrder.total_sales_order_batches_amount }}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <!-- /.box-body -->
        </div>

        <div class="box">
            <div class="box-header">
                <h3 class="box-title">收款序列</h3>
                <div class="box-tools">
                    <a :href="'/admin/receive-payment-batches/create?so_id='+this.salesOrder.id" class="btn btn-xs btn-success"><i class="fa fa-plus"></i></a>
                </div>
            </div>

            <!-- /.box-header -->
            <div class="box-body no-padding">
                <table class="table table-striped">
                    <tbody>
                    <tr>
                        <th style="width: 60px">#</th>
                        <th>NO</th>
                        <th>收款时间</th>
                        <th>收款金额</th>
                        <th>备注</th>
                        <th>操作</th>
                    </tr>

                    <tr v-for="(item,key) in salesOrder.receive_payment_batches">
                        <td>{{ ++key }}</td>
                        <td>{{ item.no }}</td>
                        <td>{{ item.receive_payment_at }}</td>
                        <td>$ {{ item.amount }}</td>
                        <td>{{ item.comment }}</td>
                        <td>
                            <a :href="'/admin/receive-payment-batches/'+item.id+'/edit'" class="btn btn-xs btn-default" ><i class="fa fa-edit"></i></a>
                            <!--                            <button class="btn btn-xs btn-default" @click="invoice(item.id)"><i class="fa fa-ticket"></i></button>-->
                        </td>
                    </tr>

                    <tr>
                        <td>合计：</td>
                        <td></td>
                        <td></td>
                        <td>$ {{ salesOrder.total_receive_payment_batches_amount }}</td>
                        <td></td>
                        <td></td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <!-- /.box-body -->
        </div>

    </div>
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
