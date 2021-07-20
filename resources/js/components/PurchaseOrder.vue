<template>
    <div>
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title"></h3>

                <div class="box-tools">
                    <div class="btn-group pull-right" style="margin-right: 5px">
                        <a :href="'/admin/projects/'+this.purchaseOrder.project_id" class="btn btn-sm btn-default" title="List">
                            <i class="fa fa-arrow-left"></i><span class="hidden-xs"> 返回</span>
                        </a>
                    </div>

                    <div class="btn-group pull-right" style="margin-right: 5px">
                        <a :href="'/admin/purchase-orders/'+this.purchaseOrder.id+'/edit'" class="btn btn-sm btn-primary" title="编辑">
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
                                <span class="info-box-icon"><i class="fa fa-cny"></i></span>

                                <div class="info-box-content">
                                    <span class="info-box-text" v-if="this.purchaseOrder.be_received > 0">待收货金额</span>
                                    <span class="info-box-text" v-else-if="this.purchaseOrder.be_received == 0">完成收货</span>
                                    <span class="info-box-text" v-else>收货金额超出采购金额</span>
                                    <span class="info-box-number">{{ this.purchaseOrder.be_received }}</span>

                                    <div class="progress">
                                        <div class="progress-bar" :style="'width:' +this.purchaseOrder.progress+'%'"></div>
                                    </div>
                                    <span class="progress-description" v-if="this.purchaseOrder.be_received == 0">耗时 {{ this.purchaseOrder.days }} 天完成收货</span>
                                    <span class="progress-description" v-else>{{ this.purchaseOrder.days }} 天增加 {{ this.purchaseOrder.progress }}%</span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box bg-yellow-gradient">
                                <span class="info-box-icon"><i class="fa fa-credit-card"></i></span>

                                <div class="info-box-content">
                                    <span class="info-box-text">待付款金额</span>
                                    <span class="info-box-number">{{ this.purchaseOrder.be_payment }}</span>

                                    <div class="progress">
                                        <div class="progress-bar" :style="'width:' +this.purchaseOrder.payment_progress+'%'"></div>
                                    </div>
                                    <span class="progress-description">付款进度 {{ this.purchaseOrder.payment_progress }}%</span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>

                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <p>销售订单编号：{{ this.purchaseOrder.sales_order.no }}</p>
                    <p>采购订单编号：{{ this.purchaseOrder.po }}</p>
                    <p>工厂：{{ this.purchaseOrder.vendor.name }}</p>
                    <p>采购总金额：{{ this.purchaseOrder.amount }}</p>
                    <p>双签合同时间：{{ this.purchaseOrder.double_signed_at }}</p>
                    <p>采购订单下单时间：{{ this.purchaseOrder.order_at }}</p>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-5">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">交货批次</h3>
                        <div class="box-tools">
                            <a :href="'/admin/delivery-batches/create?po_id='+this.purchaseOrder.id" class="btn btn-xs btn-success"><i class="fa fa-plus"></i></a>
                        </div>
                    </div>

                    <!-- /.box-header -->
                    <div class="box-body no-padding">
                        <table class="table table-striped">
                            <tbody>
                            <tr>
                                <th style="width: 60px">#</th>
                                <th>预计交期</th>
                                <th>备注</th>
                                <th>操作</th>
                            </tr>
                            <tr v-for="(item,key) in purchaseOrder.delivery_batches">
                                <td>{{ ++key }}</td>
                                <td><span data-toggle="tooltip" data-placement="top" :data-original-title="'排序编号'+item.order_by">{{ item.estimated_delivery }}</span></td>
                                <td>{{ item.comment }}</td>
                                <td>
                                    <a :href="'/admin/delivery-batches/'+item.id+'/edit'" class="btn btn-xs btn-default" ><i class="fa fa-edit"></i></a>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.box-body -->
                </div>
            </div>
            <div class="col-lg-7">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">收货序列</h3>
                        <div class="box-tools">
                            <a :href="'/admin/receipt-batches/create?po_id='+this.purchaseOrder.id" class="btn btn-xs btn-success"><i class="fa fa-plus"></i></a>
                        </div>
                    </div>

                    <!-- /.box-header -->
                    <div class="box-body no-padding">
                        <table class="table table-striped">
                            <tbody>
                            <tr>
                                <th style="width: 60px">#</th>
                                <th>批次总金额</th>
                                <th>已匹配发票金额</th>
                                <th>待匹配发票金额</th>
                                <th>实际交期</th>
                                <th>备注</th>
                                <th>操作</th>
                            </tr>

                            <tr v-for="(item,key) in purchaseOrder.receipt_batches">
                                <td>{{ ++key }}</td>
                                <td>¥ {{ item.amount }}</td>
                                <td>¥ {{ item.matched_amount.toFixed(2) }}</td>
                                <td>
                                    <label class="label label-default" v-if="item.amount == null">-</label>
                                    <label class="label label-danger" v-else-if="(item.amount - item.matched_amount).toFixed(2) < 0">¥ {{ (item.amount - item.matched_amount).toFixed(2) }}</label>
                                    <label v-else-if="(item.amount - item.matched_amount).toFixed(2) == 0"><i class="text-success fa fa-check"></i></label>
                                    <label class="label label-default" v-else>¥ {{ (item.amount - item.matched_amount).toFixed(2) }}</label>
                                </td>
                                <td>{{ item.receipt_at }}</td>
                                <td>{{ item.comment }}</td>
                                <td>
                                    <a :href="'/admin/receipt-batches/'+item.id+'/edit'" class="btn btn-xs btn-default" ><i class="fa fa-edit"></i></a>
                                    <button class="btn btn-xs btn-default" @click="invoice(item.id)"><i class="fa fa-ticket"></i></button>
                                </td>
                            </tr>

                            <tr>
                                <td>统计：</td>
                                <td>¥{{ purchaseOrder.batches_total_amount }}</td>
                                <td>¥{{ purchaseOrder.batches_matched_amount }}</td>
                                <td>¥{{ purchaseOrder.batches_unmatched_amount }}</td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.box-body -->
                </div>
            </div>

        </div>



        <div class="box">
            <div class="box-header">
                <h3 class="box-title">付款序列</h3>
                <div class="box-tools">
                    <a :href="'/admin/payment-batches/create?po_id='+this.purchaseOrder.id" class="btn btn-xs btn-success"><i class="fa fa-plus"></i></a>
                </div>
            </div>

            <!-- /.box-header -->
            <div class="box-body no-padding">
                <table class="table table-striped">
                    <tbody>
                    <tr>
                        <th style="width: 60px">#</th>
                        <th>付款金额</th>
                        <th>已匹配发票金额</th>
                        <th>待匹配发票金额</th>
                        <th>付款时间</th>
                        <th>备注</th>
                        <th>操作</th>
                    </tr>

                    <tr v-for="(item,key) in purchaseOrder.payment_batches">
                        <td>{{ ++key }}</td>
                        <td>¥ {{ item.amount }}</td>
                        <td>¥ {{ item.matched_amount }}</td>
                        <td>
                            <label class="label label-danger" v-if="item.amount - item.matched_amount < 0">¥ {{ item.amount - item.matched_amount }}</label>
                            <label v-else-if="item.amount - item.matched_amount == 0"><i class="text-success fa fa-check"></i></label>
                            <label class="label label-default" v-else>¥ {{ (item.amount - item.matched_amount).toFixed(2) }}</label>
                        </td>
                        <td>{{ item.payment_at }}</td>
                        <td>{{ item.comment }}</td>
                        <td>
                            <a :href="'/admin/payment-batches/'+item.id+'/edit'" class="btn btn-xs btn-default" ><i class="fa fa-edit"></i></a>
                            <button class="btn btn-xs btn-default" @click="payment(item.id)"><i class="fa fa-ticket"></i></button>
                        </td>
                    </tr>

                    <tr>
                        <td>统计：</td>
                        <td>¥{{ purchaseOrder.payment_batches_total_amount }}</td>
                        <td>¥{{ purchaseOrder.payment_batches_matched_total_amount }}</td>
                        <td>¥{{ purchaseOrder.payment_batches_unmatched_total_amount }}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <!-- /.box-body -->
        </div>


        <!-- Modal -->
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false" data-backdrop="static">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">
                            <span>收货 - </span>
                            <span>¥ {{ res.amount }}</span>
                            <span style="margin-left: 20px"><i class="fa fa-clock-o"></i> {{ res.receipt_at }}</span>
                        </h4>
                    </div>
                    <div class="modal-body">

                        <div class="row" v-if="res.receipt_batch_total_amount == res.amount">
                            <div class="col-md-12">
                                <div class="small-box bg-green-gradient">
                                    <div class="inner">
                                        <h3>¥ {{ res.amount }}</h3>
                                        <p>全部金额匹配完成</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fa fa-cny"></i>
                                    </div>
                                    <span class="small-box-footer">
                                            收货时间 {{ res.receipt_at }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="row" v-else>
                            <div class="col-md-6">
                                <div class="small-box bg-aqua-gradient">
                                    <div class="inner">
                                        <h3>¥ {{ res.amount}}</h3>
                                        <p>批次总金额</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fa fa-cny"></i>
                                    </div>
                                    <span class="small-box-footer">
                                            收货时间 {{ res.receipt_at }}
                                        </span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="small-box bg-yellow-gradient">
                                    <div class="inner">
                                        <h3>¥ {{ (res.amount - res.receipt_batch_total_amount).toFixed(2) }}</h3>
                                        <p>待匹配金额</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fa fa-cny"></i>
                                    </div>
                                    <span class="small-box-footer">
                                            &nbsp;
                                        </span>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title" v-if="res.receipt_batch_total_amount == res.amount"><i class="fa fa-check text-success"></i> 匹配完成的发票</h3>
                                <h3 class="panel-title" v-else>已匹配发票</h3>
                            </div>
                            <div class="panel-body">
                                <table class="table" v-if="res.receipt_batch && res.receipt_batch.length">
                                    <thead>
                                    <tr>
                                        <td>发票号</td>
                                        <td>开票日期</td>
                                        <td>发票备注</td>
                                        <td>发票总金额</td>
                                        <td>本次使用金额</td>
                                        <td>操作</td>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="item in res.receipt_batch">
                                        <td>{{ item.invoice.invoice_no }}</td>
                                        <td>{{ item.invoice.billing_time }}</td>
                                        <td>{{ item.invoice.title }}</td>
                                        <td>{{ item.invoice.amount }}</td>
                                        <td>{{ item.amount }}</td>
                                        <td><a href="##" title="撤销" @click="resetMatch(item.id)"><i class="fa fa-history"></i></a></td>
                                    </tr>
                                    </tbody>
                                </table>
                                <div v-else style="text-align: center;font-size: 30px;padding: 40px;color: #d2d2d2;">
                                    您还没有匹配发票
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-info" v-if="res.receipt_batch_total_amount < res.amount">
                            <div class="panel-heading">
                                <h3 class="panel-title">等待匹配的发票</h3>
                            </div>
                            <div class="panel-body">
                                <table class="table" v-if="res.invoices && res.invoices.length">
                                    <thead>
                                    <tr>
                                        <td>发票号</td>
                                        <td>开票日期</td>
                                        <td>发票备注</td>
                                        <td>发票总金额</td>
                                        <td>未关联金额</td>
                                        <td>关联金额</td>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="item in res.invoices">
                                        <td><label class="form-control">{{ item.invoice_no }}</label></td>
                                        <td><label class="form-control">{{ item.billing_time }}</label></td>
                                        <td><label class="form-control">{{ item.title }}</label></td>
                                        <td><label class="form-control">{{ item.amount }}</label></td>
                                        <td><label class="form-control">{{ item.over_amount }}</label></td>
                                        <td>
                                            <div class="form-group">
                                                <input class="form-control" v-model="item.match_amount">
                                            </div>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                                <div v-else style="text-align: center;font-size: 30px;padding: 40px;color: #d2d2d2;">
                                    找不到可以匹配的发票
                                </div>
                            </div>
                        </div>


                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                        <button type="button" class="btn btn-primary" @click="saveMatch" v-if="res.receipt_batch_total_amount < res.amount">保存关联</button>
                    </div>
                </div>
            </div>
        </div>



        <div class="modal fade" id="paymentModal" tabindex="-1" role="dialog" aria-labelledby="paymentModalLabel" aria-hidden="false" data-backdrop="static">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="paymentModalLabel">
                            <span>付款 - </span>
                            <span>¥ {{ payment_res.amount }}</span>
                            <span style="margin-left: 20px"><i class="fa fa-clock-o"></i> {{ payment_res.payment_at }}</span>
                        </h4>
                    </div>
                    <div class="modal-body">

                        <div class="row" v-if="payment_res.payment_batch_total_amount == payment_res.amount">
                            <div class="col-md-12">
                                <div class="small-box bg-green-gradient">
                                    <div class="inner">
                                        <h3>¥ {{ payment_res.amount }}</h3>
                                        <p>全部金额匹配完成</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fa fa-cny"></i>
                                    </div>
                                    <span class="small-box-footer">
                                            收货时间 {{ payment_res.payment_at }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="row" v-else>
                            <div class="col-md-6">
                                <div class="small-box bg-aqua-gradient">
                                    <div class="inner">
                                        <h3>¥ {{ payment_res.amount}}</h3>
                                        <p>批次总金额</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fa fa-cny"></i>
                                    </div>
                                    <span class="small-box-footer">
                                            收货时间 {{ payment_res.payment_at }}
                                        </span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="small-box bg-yellow-gradient">
                                    <div class="inner">
                                        <h3>¥ {{ (payment_res.amount - payment_res.payment_batch_total_amount).toFixed(2) }}</h3>
                                        <p>待匹配金额</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fa fa-cny"></i>
                                    </div>
                                    <span class="small-box-footer">
                                            &nbsp;
                                        </span>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title" v-if="payment_res.payment_batch_total_amount == payment_res.amount"><i class="fa fa-check text-success"></i> 匹配完成的发票</h3>
                                <h3 class="panel-title" v-else>已匹配发票</h3>
                            </div>
                            <div class="panel-body">
                                <table class="table" v-if="payment_res.payment_batch && payment_res.payment_batch.length">
                                    <thead>
                                    <tr>
                                        <td>发票号</td>
                                        <td>开票日期</td>
                                        <td>发票备注</td>
                                        <td>发票总金额</td>
                                        <td>本次使用金额</td>
                                        <td>操作</td>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="item in payment_res.payment_batch">
                                        <td>{{ item.invoice.invoice_no }}</td>
                                        <td>{{ item.invoice.billing_time }}</td>
                                        <td>{{ item.invoice.title }}</td>
                                        <td>{{ item.invoice.amount }}</td>
                                        <td>{{ item.amount }}</td>
                                        <td><a href="##" title="撤销" @click="resetPaymentMatch(item.id)"><i class="fa fa-history"></i></a></td>
                                    </tr>
                                    </tbody>
                                </table>
                                <div v-else style="text-align: center;font-size: 30px;padding: 40px;color: #d2d2d2;">
                                    您还没有匹配发票
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-info" v-if="payment_res.payment_batch_total_amount < payment_res.amount">
                            <div class="panel-heading">
                                <h3 class="panel-title">等待匹配的发票</h3>
                            </div>
                            <div class="panel-body">
                                <table class="table" v-if="payment_res.invoices && payment_res.invoices.length">
                                    <thead>
                                    <tr>
                                        <td>发票号</td>
                                        <td>开票日期</td>
                                        <td>发票备注</td>
                                        <td>发票总金额</td>
                                        <td>未关联金额</td>
                                        <td>关联金额</td>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="item in payment_res.invoices">
                                        <td><label class="form-control">{{ item.invoice_no }}</label></td>
                                        <td><label class="form-control">{{ item.billing_time }}</label></td>
                                        <td><label class="form-control">{{ item.title }}</label></td>
                                        <td><label class="form-control">{{ item.amount }}</label></td>
                                        <td><label class="form-control">{{ item.over_amount }}</label></td>
                                        <td>
                                            <div class="form-group">
                                                <input class="form-control" v-model="item.match_amount">
                                            </div>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                                <div v-else style="text-align: center;font-size: 30px;padding: 40px;color: #d2d2d2;">
                                    找不到可以匹配的发票
                                </div>
                            </div>
                        </div>


                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                        <button type="button" class="btn btn-primary" @click="savePaymentMatch" v-if="payment_res.payment_batch_total_amount < payment_res.amount">保存关联</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
    export default {
        data() {
            return {
                purchaseOrder : [],
                res:[],
                payment_res:[]
            }
        },

        props: [
            'po',
        ],

        mounted() {
            $('#myModal').on('hidden.bs.modal', () => {location.reload()})
            $('#paymentModal').on('hidden.bs.modal', () => {location.reload()})
        },

        created() {
            this.purchaseOrder = JSON.parse(this.po);
        },

        methods: {
            invoice(id){
                axios.get("/admin/associated-invoice/" + id).then(response => {
                    this.res = response.data.data;
                }).catch(error => {
                    toastr.error(error.response.data.message)
                });
                $('#myModal').modal('show')
            },

            payment(id){
                axios.get("/admin/associated-invoice/payment/" + id).then(response => {
                    this.payment_res = response.data.data;
                }).catch(error => {
                    toastr.error(error.response.data.message)
                });
                $('#paymentModal').modal('show')
            },

            saveMatch(){
                axios({
                    method: 'post',
                    data:this.res.invoices,
                    url: '/admin/associated-invoice/' + this.res.id,
                }).then(response => {
                    swal(
                        "SUCCESS",
                        response.data.data,
                        'success'
                    ).then(()=>{
                        this.invoice(this.res.id)
                    })

                }).catch(error =>{
                    toastr.error(error.response.data.message)
                })
            },

            savePaymentMatch(){
                axios({
                    method: 'post',
                    data:this.payment_res.invoices,
                    url: '/admin/associated-invoice/payment/' + this.payment_res.id,
                }).then(response => {
                    swal(
                        "SUCCESS",
                        response.data.data,
                        'success'
                    ).then(()=>{
                        this.payment(this.payment_res.id)
                    })

                }).catch(error =>{
                    toastr.error(error.response.data.message)
                })
            },

            //撤销匹配
            resetMatch(id){
                swal({
                    title: '确定撤销?',
                    type: 'info',
                    showCancelButton: true,
                    confirmButtonText: '确定',
                    cancelButtonText: '取消'
                }).then( (isConfirm) => {
                    if(isConfirm.value == true){
                        axios({
                            method: 'post',
                            url: '/admin/associated-invoice/delete/' + id,
                        }).then(response => {
                            swal(
                                "SUCCESS",
                                response.data.data,
                                'success'
                            ).then( ()=>{
                                this.invoice(this.res.id)
                            })

                        }).catch(error =>{
                            toastr.error(error.response.data.message)
                        })
                    }
                })
            },

            resetPaymentMatch(id){
                swal({
                    title: '确定撤销?',
                    type: 'info',
                    showCancelButton: true,
                    confirmButtonText: '确定',
                    cancelButtonText: '取消'
                }).then( (isConfirm) => {
                    if(isConfirm.value == true){
                        axios({
                            method: 'post',
                            url: '/admin/associated-invoice/payment/delete/' + id,
                        }).then(response => {
                            swal(
                                "SUCCESS",
                                response.data.data,
                                'success'
                            ).then( ()=>{
                                this.payment(this.payment_res.id)
                            })

                        }).catch(error =>{
                            toastr.error(error.response.data.message)
                        })
                    }
                })
            }


        }
    }
</script>
