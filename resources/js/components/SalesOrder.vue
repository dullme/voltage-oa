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
                        <th>已匹配水单金额</th>
                        <th>待匹配水单金额</th>
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
                        <td>
                            <label v-if="item.is_matched"><i class="text-success fa fa-check"></i></label>
                            <span v-else-if="item.matched_amount == 0 ">$ {{ item.matched_amount }}</span>
                            <label class="label label-danger" v-else-if="item.matched_amount > item.amount">$ {{ item.matched_amount }}</label>
                            <label class="label label-default" v-else>$ {{ item.matched_amount }}</label>
                        </td>

                        <td>
                            <label class="label label-default" v-if="item.unmatched_amount > 0">$ {{ item.unmatched_amount }}</label>
                            <label class="label label-danger" v-else-if="item.unmatched_amount < 0">$ {{ item.unmatched_amount }}</label>
                            <span v-else>-</span>
                        </td>
                        <td>{{ item.declaration_number }}</td>
                        <td><a v-if="item.file" :href="'/uploads/'+item.file" target="_blank"><i class="fa fa-download"></i></a></td>
                        <td>{{ item.comment }}</td>
                        <td>
                            <a :href="'/admin/sales-order-batches/'+item.id+'/edit'" class="btn btn-xs btn-default" ><i class="fa fa-edit"></i></a>
                            <button class="btn btn-xs btn-default" @click="shipment(item.id)"><i class="fa fa-ticket"></i></button>
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
                        <th>已匹配水单金额</th>
                        <th>待匹配水单金额</th>
                        <th>备注</th>
                        <th>操作</th>
                    </tr>
                    <tr v-for="(item,key) in salesOrder.receive_payment_batches">
                        <td>{{ ++key }}</td>
                        <td>{{ item.no }}</td>
                        <td>{{ item.receive_payment_at }}</td>
                        <td>$ {{ item.amount }}</td>
                        <td>
                            <label v-if="item.is_matched"><i class="text-success fa fa-check"></i></label>
                            <span v-else-if="item.matched_amount == 0 ">$ {{ item.matched_amount }}</span>
                            <label class="label label-danger" v-else-if="item.matched_amount > item.amount">$ {{ item.matched_amount }}</label>
                            <label class="label label-default" v-else>$ {{ item.matched_amount }}</label>
                        </td>

                        <td>
                            <label class="label label-default" v-if="item.unmatched_amount > 0">$ {{ item.unmatched_amount }}</label>
                            <label class="label label-danger" v-else-if="item.unmatched_amount < 0">$ {{ item.unmatched_amount }}</label>
                            <span v-else>-</span>
                        </td>
                        <td>{{ item.comment }}</td>
                        <td>
                            <a :href="'/admin/receive-payment-batches/'+item.id+'/edit'" class="btn btn-xs btn-default" ><i class="fa fa-edit"></i></a>
                            <button class="btn btn-xs btn-default" @click="receive(item.id)"><i class="fa fa-ticket"></i></button>
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

        <!--发货序列模态框-->
        <div class="modal fade" id="shipmentModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false" data-backdrop="static">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myShipmentModalLabel">
                            <span>DO 编号： </span>
                            <span>{{ res.no }}</span>
                        </h4>
                    </div>
                    <div class="modal-body">

                        <div class="row" v-if="res.sales_order_batch_receives_amount == res.amount">
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
                                            发货时间 {{ res.delivery_at }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="row" v-else>
                            <div class="col-md-6">
                                <div class="small-box bg-aqua-gradient">
                                    <div class="inner">
                                        <h3>$ {{ res.amount}}</h3>
                                        <p>发货序列批次总金额</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fa fa-dollar"></i>
                                    </div>
                                    <span class="small-box-footer">
                                            发货时间 {{ res.delivery_at }}
                                        </span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="small-box bg-yellow-gradient">
                                    <div class="inner">
                                        <h3>$ {{ (res.amount - res.sales_order_batch_receives_amount).toFixed(2) }}</h3>
                                        <p>待匹配金额</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fa fa-dollar"></i>
                                    </div>
                                    <span class="small-box-footer">
                                            &nbsp;
                                        </span>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title" v-if="res.sales_order_batch_receives_amount == res.amount"><i class="fa fa-check text-success"></i> 匹配完成的银行水单</h3>
                                <h3 class="panel-title" v-else>已匹配水单</h3>
                            </div>
                            <div class="panel-body">
                                <table class="table" v-if="res.sales_order_batch_receives && res.sales_order_batch_receives.length">
                                    <thead>
                                    <tr>
                                        <td>银行水单</td>
                                        <td>收款时间</td>
                                        <td>水单备注</td>
                                        <td>水单总金额</td>
                                        <td>本次匹配金额</td>
                                        <td>操作</td>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="item in res.sales_order_batch_receives">
                                        <td>{{ item.receive.bank_receipt }}</td>
                                        <td>{{ item.receive.receive_payment_at }}</td>
                                        <td>{{ item.receive.remark }}</td>
                                        <td>$ {{ item.receive.amount }}</td>
                                        <td>$ {{ item.amount }}</td>
                                        <td><a href="##" title="撤销" @click="resetShipmentMatch(item.id)"><i class="fa fa-history"></i></a></td>
                                    </tr>
                                    </tbody>
                                </table>
                                <div v-else style="text-align: center;font-size: 30px;padding: 40px;color: #d2d2d2;">
                                    您还没有匹配银行水单
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-info" v-if="res.sales_order_batch_receives_amount < res.amount">
                            <div class="panel-heading">
                                <h3 class="panel-title">等待匹配的银行水单</h3>
                            </div>
                            <div class="panel-body">
                                <table class="table" v-if="res.receives && res.receives.length">
                                    <thead>
                                    <tr>
                                        <td>银行水单</td>
                                        <td>收款时间</td>
                                        <td>水单备注</td>
                                        <td>水单总金额</td>
                                        <td>未关联金额</td>
                                        <td>关联金额</td>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="item in res.receives">
                                        <td><label class="form-control">{{ item.bank_receipt }}</label></td>
                                        <td><label class="form-control">{{ item.receive_payment_at }}</label></td>
                                        <td><label class="form-control">{{ item.remark }}</label></td>
                                        <td><label class="form-control">$ {{ item.amount }}</label></td>
                                        <td><label class="form-control">$ {{ item.over_amount }}</label></td>
                                        <td>
                                            <div class="form-group">
                                                <input class="form-control" v-model="item.match_amount">
                                            </div>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                                <div v-else style="text-align: center;font-size: 30px;padding: 40px;color: #d2d2d2;">
                                    找不到可以匹配的银行水单
                                </div>
                            </div>
                        </div>


                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                        <button type="button" class="btn btn-primary" @click="saveShipmentMatch" v-if="res.sales_order_batch_receives_amount < res.amount">保存关联</button>
                    </div>
                </div>
            </div>
        </div>


        <!--收款序列模态框-->
        <div class="modal fade" id="receiveModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false" data-backdrop="static">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">
                            <span>收款 </span>
                            <span>¥ {{ res.amount }}</span>
                            <span style="margin-left: 20px"><i class="fa fa-clock-o"></i> {{ res.receive_payment_at }}</span>
                        </h4>
                    </div>
                    <div class="modal-body">

                        <div class="row" v-if="res.receive_payment_batch_receives_amount == res.amount">
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
                                            收款时间 {{ res.receive_payment_at }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="row" v-else>
                            <div class="col-md-6">
                                <div class="small-box bg-aqua-gradient">
                                    <div class="inner">
                                        <h3>$ {{ res.amount}}</h3>
                                        <p>收款序列总金额</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fa fa-dollar"></i>
                                    </div>
                                    <span class="small-box-footer">
                                            收款时间 {{ res.receive_payment_at }}
                                        </span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="small-box bg-yellow-gradient">
                                    <div class="inner">
                                        <h3>$ {{ (res.amount - res.receive_payment_batch_receives_amount).toFixed(2) }}</h3>
                                        <p>待匹配金额</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fa fa-dollar"></i>
                                    </div>
                                    <span class="small-box-footer">
                                            &nbsp;
                                        </span>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title" v-if="res.receive_payment_batch_receives_amount == res.amount"><i class="fa fa-check text-success"></i> 匹配完成的银行水单</h3>
                                <h3 class="panel-title" v-else>已匹配水单</h3>
                            </div>
                            <div class="panel-body">
                                <table class="table" v-if="res.receive_payment_batch_receives && res.receive_payment_batch_receives.length">
                                    <thead>
                                    <tr>
                                        <td>银行水单</td>
                                        <td>收款时间</td>
                                        <td>水单备注</td>
                                        <td>水单总金额</td>
                                        <td>本次匹配金额</td>
                                        <td>操作</td>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="item in res.receive_payment_batch_receives">
                                        <td>{{ item.receive.bank_receipt }}</td>
                                        <td>{{ item.receive.receive_payment_at }}</td>
                                        <td>{{ item.receive.remark }}</td>
                                        <td>$ {{ item.receive.amount }}</td>
                                        <td>$ {{ item.amount }}</td>
                                        <td><a href="##" title="撤销" @click="resetMatch(item.id)"><i class="fa fa-history"></i></a></td>
                                    </tr>
                                    </tbody>
                                </table>
                                <div v-else style="text-align: center;font-size: 30px;padding: 40px;color: #d2d2d2;">
                                    您还没有匹配银行水单
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-info" v-if="res.receive_payment_batch_receives_amount < res.amount">
                            <div class="panel-heading">
                                <h3 class="panel-title">等待匹配的银行水单</h3>
                            </div>
                            <div class="panel-body">
                                <table class="table" v-if="res.receives && res.receives.length">
                                    <thead>
                                    <tr>
                                        <td>银行水单</td>
                                        <td>收款时间</td>
                                        <td>水单备注</td>
                                        <td>水单总金额</td>
                                        <td>未关联金额</td>
                                        <td>关联金额</td>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="item in res.receives">
                                        <td><label class="form-control">{{ item.bank_receipt }}</label></td>
                                        <td><label class="form-control">{{ item.receive_payment_at }}</label></td>
                                        <td><label class="form-control">{{ item.remark }}</label></td>
                                        <td><label class="form-control">$ {{ item.amount }}</label></td>
                                        <td><label class="form-control">$ {{ item.over_amount }}</label></td>
                                        <td>
                                            <div class="form-group">
                                                <input class="form-control" v-model="item.match_amount">
                                            </div>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                                <div v-else style="text-align: center;font-size: 30px;padding: 40px;color: #d2d2d2;">
                                    找不到可以匹配的银行水单
                                </div>
                            </div>
                        </div>


                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                        <button type="button" class="btn btn-primary" @click="saveMatch" v-if="res.receive_payment_batch_receives_amount < res.amount">保存关联</button>
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
                        salesOrder : [],
                        res:[],
                        payment_res:[]
                    }
                },

                props: [
                    'so',
                ],

                mounted() {
                    $('#receiveModal').on('hidden.bs.modal', () => {location.reload()})
                    $('#shipmentModal').on('hidden.bs.modal', () => {location.reload()})
                },

                created() {
                    this.salesOrder = JSON.parse(this.so);
                },

                methods: {
                    shipment(id){
                        axios.get("/admin/associated-receive/" + id).then(response => {
                            this.res = response.data.data;
                            $('#shipmentModal').modal('show')
                        }).catch(error => {
                            toastr.error(error.response.data.message)
                        });
                    },

                    saveShipmentMatch(){
                        axios({
                            method: 'post',
                            data:this.res.receives,
                            url: '/admin/associated-receive/' + this.res.id,
                        }).then(response => {
                            swal(
                                "SUCCESS",
                                response.data.data,
                                'success'
                            ).then(()=>{
                                this.shipment(this.res.id) //需要修改
                            })

                        }).catch(error =>{
                            toastr.error(error.response.data.message)
                        })
                    },

                    resetShipmentMatch(id){
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
                                    url: '/admin/associated-receive/delete/' + id,
                                }).then(response => {
                                    swal(
                                        "SUCCESS",
                                        response.data.data,
                                        'success'
                                    ).then( ()=>{
                                        this.shipment(this.res.id)
                                    })

                                }).catch(error =>{
                                    toastr.error(error.response.data.message)
                                })
                            }
                        })
                    },


                    receive(id){
                        axios.get("/admin/associated-receive/water-bill/" + id).then(response => {
                            this.res = response.data.data;
                            $('#receiveModal').modal('show')
                        }).catch(error => {
                            toastr.error(error.response.data.message)
                        });
                    },

                    saveMatch(){
                        axios({
                            method: 'post',
                            data:this.res.receives,
                            url: '/admin/associated-receive/water-bill/' + this.res.id,
                        }).then(response => {
                            swal(
                                "SUCCESS",
                                response.data.data,
                                'success'
                            ).then(()=>{
                                this.receive(this.res.id) //需要修改
                            })

                        }).catch(error =>{
                            toastr.error(error.response.data.message)
                        })
                    },

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
                                    url: '/admin/associated-receive/water-bill/delete/' + id,
                                }).then(response => {
                                    swal(
                                        "SUCCESS",
                                        response.data.data,
                                        'success'
                                    ).then( ()=>{
                                        this.receive(this.res.id)
                                    })

                                }).catch(error =>{
                                    toastr.error(error.response.data.message)
                                })
                            }
                        })
                    },

                }
            }
        </script>
